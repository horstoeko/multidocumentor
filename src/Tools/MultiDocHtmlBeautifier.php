<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Tools;

/**
 * Class representing a HTML beautifier
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocHtmlBeautifier
{
    private $options;

    private $input;
    
    private $inputLength;
    
    private $output;
    
    private $pos = 0;
    
    private $currentMode = 'CONTENT';
    
    private $tags = [
        'parent'        => 'parent1',
        'parentcount'    => 1,
        'parent1'        => ''
    ];
    
    private $tagType = '';
    
    private $tokenText = '';
    
    private $lastToken = '';
    
    private $lastText = '';
    
    private $tokenType = '';
    
    private $newlines = 0;
    
    private $indentContent;
    
    private $indentLevel = 0;
    
    private $lineCharCount = 0;
    
    private $indentString;
    
    private $cssBeautify;
    
    private $jsBeautify;

    private $whitespace = ["\n", "\r", "\t", " "];

    //all the single tags for HTML
    private $singleToken = [
        'br', 'input', 'link', 'meta', '!doctype', 'basefont', 'base', 'area',
        'hr', 'wbr', 'param', 'img', 'isindex', '?xml', 'embed', '?php', '?', '?='
    ];

    //for tags that need a line of whitespace before them
    private $extraLiners = ['head', 'body', '/html'];

    public function __construct($options = [], $css_beautify = null, $js_beautify = null)
    {
        $this->set_options($options);

        $this->cssBeautify = ($css_beautify && is_callable($css_beautify)) ? $css_beautify : false;
        $this->jsBeautify = ($js_beautify && is_callable($js_beautify)) ? $js_beautify : false;

        $this->indentContent = $this->options['indent_inner_html']; //count to see if wrap_line_length was exceeded
        $this->indentString = str_repeat($this->options['indent_char'], $this->options['indent_size']);
    }

    public function set_options($options)
    {
        if (isset($options['indent_inner_html'])) {
            $this->options['indent_inner_html'] = (bool)$options['indent_inner_html'];
        } else {
            $this->options['indent_inner_html'] = false;
        }

        $this->options['indent_size'] = isset($options['indent_size']) ? (int)$options['indent_size'] : 4;

        $this->options['indent_char'] = isset($options['indent_char']) ? (string)$options['indent_char'] : ' ';

        if (isset($options['indent_scripts']) && in_array($options['indent_scripts'], ['keep', 'separate', 'normal'])) {
            $this->options['indent_scripts'] = $options['indent_scripts'];
        } else {
            $this->options['indent_scripts'] = 'normal';
        }

        $this->options['wrap_line_length'] = isset($options['wrap_line_length']) ? (int)$options['wrap_line_length'] : 32786;

        if (isset($options['unformatted']) && is_array($options['unformatted'])) {
            $this->options['unformatted'] = $options['unformatted'];
        } else {
            $this->options['unformatted'] = [
                'a', 'span', 'bdo', 'em', 'strong', 'dfn', 'code', 'samp', 'kbd', 'var', 'cite', 'abbr',
                'acronym', 'q', 'sub', 'sup', 'tt', 'i', 'b', 'big', 'small', 'u', 's', 'strike',
                'font', 'ins', 'del', 'pre', 'address', 'dt', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ];
        }

        $this->options['preserve_newlines'] = isset($options['preserve_newlines']) ? (bool)$options['preserve_newlines'] : true;

        if ($this->options['preserve_newlines'] && isset($options['max_preserve_newlines'])) {
            $this->options['max_preserve_newlines'] = (int)$options['max_preserve_newlines'];
        } else {
            $this->options['max_preserve_newlines'] = 0;
        }
    }

    private function traverse_whitespace()
    {
        $input_char = $this->input[$this->pos] ?? '';
        if ($input_char && in_array($input_char, $this->whitespace)) {

            $this->newlines = 0;
            while ($input_char && in_array($input_char, $this->whitespace)) {
                if ($this->options['preserve_newlines']
                    && $input_char === "\n"
                    && $this->newlines <= $this->options['max_preserve_newlines']
                ) {
                    $this->newlines += 1;
                }

                $this->pos++;
                $input_char = $this->input[$this->pos] ?? '';
            }
            
            return true;
        }
        
        return false;
    }

    //function to capture regular content between tags
    private function get_content()
    {
        $input_char = '';
        $content = [];
        $space = false; //if a space is needed

        while (isset($this->input[$this->pos]) && $this->input[$this->pos] !== '<') {

            if ($this->pos >= $this->inputLength) {
                return count($content) > 0 ? implode('', $content) : ['', 'TK_EOF'];
            }

            if ($this->traverse_whitespace()) {
                if (count($content) > 0) {
                    $space = true;
                }

                continue; //don't want to insert unnecessary space
            }

            $input_char = $this->input[$this->pos];
            $this->pos++;

            if ($space) {
                if ($this->lineCharCount >= $this->options['wrap_line_length']) { //insert a line when the wrap_line_length is reached
                    $this->print_newline(false, $content);
                    $this->print_indentation($content);
                } else {
                    $this->lineCharCount++;
                    $content[] = ' ';
                }

                $space = false;
            }

            $this->lineCharCount++;
            $content[] = $input_char; //letter at-a-time (or string) inserted to an array
        }

        return count($content) > 0 ? implode('', $content) : '';
    }

    //get the full content of a script or style to pass to js_beautify
    private function get_contents_to($name)
    {
        if ($this->pos === $this->inputLength) {
            return ['', 'TK_EOF'];
        }
        
        $content = '';

        $reg_array = [];
        preg_match('#</' . preg_quote($name, '#') . '\\s*>#im', $this->input, $reg_array, PREG_OFFSET_CAPTURE, $this->pos);
        $end_script = $reg_array !== [] ? ($reg_array[0][1]) : $this->inputLength; //absolute end of script

        if ($this->pos < $end_script) { //get everything in between the script tags
            $content = substr($this->input, $this->pos, max($end_script - $this->pos, 0));
            $this->pos = $end_script;
        }

        return $content;
    }

    //function to record a tag and its parent in this.tags Object
    private function record_tag($tag)
    {
        if (isset($this->tags[$tag . 'count'])) { //check for the existence of this tag type
            $this->tags[$tag . 'count']++;
            $this->tags[$tag . $this->tags[$tag . 'count']] = $this->indentLevel; //and record the present indent level
        } else { //otherwise initialize this tag type
            $this->tags[$tag . 'count'] = 1;
            $this->tags[$tag . $this->tags[$tag . 'count']] = $this->indentLevel; //and record the present indent level
        }

        $this->tags[$tag . $this->tags[$tag . 'count'] . 'parent'] = $this->tags['parent']; //set the parent (i.e. in the case of a div this.tags.div1parent)
        $this->tags['parent'] = $tag . $this->tags[$tag . 'count']; //and make this the current parent (i.e. in the case of a div 'div1')
    }


    //function to retrieve the opening tag to the corresponding closer
    private function retrieve_tag($tag)
    {
        if (isset($this->tags[$tag . 'count'])) { //if the openener is not in the Object we ignore it
            $temp_parent = $this->tags['parent']; //check to see if it's a closable tag.
            while ($temp_parent) { //till we reach '' (the initial value);
                if ($tag . $this->tags[$tag . 'count'] === $temp_parent) { //if this is it use it
                    break;
                }

                $temp_parent = $this->tags[$temp_parent . 'parent'] ?? ''; //otherwise keep on climbing up the DOM Tree
            }
            
            if ($temp_parent) { //if we caught something
                $this->indentLevel = $this->tags[$tag . $this->tags[$tag . 'count']]; //set the indent_level accordingly
                $this->tags['parent'] = $this->tags[$temp_parent . 'parent']; //and set the current parent
            }
            
            unset($this->tags[$tag . $this->tags[$tag . 'count'] . 'parent']); //delete the closed tags parent reference...
            unset($this->tags[$tag . $this->tags[$tag . 'count']]); //...and the tag itself
            if ($this->tags[$tag . 'count'] === 1) {
                unset($this->tags[$tag . 'count']);
            } else {
                $this->tags[$tag . 'count']--;
            }
        }
    }

    //function to get a full tag and parse its type
    private function get_tag($peek = false)
    {
        $input_char = '';
        $content = [];
        $comment = '';
        $space = false;
        $tag_start = 0;
        $tag_end = 0;
        $tag_start_char = false;
        $orig_pos = $this->pos;
        $orig_line_char_count = $this->lineCharCount;

        do {
            if ($this->pos >= $this->inputLength) {
                if ($peek) {
                    $this->pos = $orig_pos;
                    $this->lineCharCount = $orig_line_char_count;
                }

                return count($content) > 0 ? implode('', $content) : ['', 'TK_EOF'];
            }

            $input_char = $this->input[$this->pos];
            $this->pos++;

            if (in_array($input_char, $this->whitespace)) { //don't want to insert unnecessary space
                $space = true;
                continue;
            }

            if ($input_char === "'" || $input_char === '"') {
                $input_char .= $this->get_unformatted($input_char);
                $space = true;
            }

            if ($input_char === '=') { //no space before =
                $space = false;
            }

            if (count($content) && $content[count($content) - 1] !== '=' && $input_char !== '>' && $space) {
                //no space after = or before >
                if ($this->lineCharCount >= $this->options['wrap_line_length']) {
                    $this->print_newline(false, $content);
                    $this->print_indentation($content);
                } else {
                    $content[] = ' ';
                    $this->lineCharCount++;
                }

                $space = false;
            }

            if ($input_char === '<' && ($tag_start_char === false || ($tag_start_char === '' || $tag_start_char === '0'))) {
                $tag_start = $this->pos - 1;
                $tag_start_char = '<';
            }

            $this->lineCharCount++;
            $content[] = $input_char; //inserts character at-a-time (or string)

            if (isset($content[1]) && $content[1] === '!') { //if we're in a comment, do something special
                // We treat all comments as literals, even more than preformatted tags
                // we just look for the appropriate close tag
                $content = [$this->get_comment($tag_start)];
                break;
            }
        } while ($input_char !== '>');

        $tag_complete = implode('', $content);

        if (strpos($tag_complete, ' ') !== false) { //if there's whitespace, thats where the tag name ends
            $tag_index = strpos($tag_complete, ' ');
        } else { //otherwise go with the tag ending
            $tag_index = strpos($tag_complete, '>');
        }

        if ($tag_complete[0] === '<') {
            $tag_offset = 1;
        } else {
            $tag_offset = $tag_complete[2] === '#' ? 3 : 2;
        }

        $tag_check = strtolower(substr($tag_complete, $tag_offset, max($tag_index - $tag_offset, 0)));

        if ($tag_complete[strlen($tag_complete) - 2] === '/'
            || in_array($tag_check, $this->singleToken)
        ) {
            //if this tag name is a single tag type (either in the list or has a closing /)
            if (!$peek) {
                $this->tagType = 'SINGLE';
            }

        } elseif ($tag_check === 'script') {
            if (!$peek) {
                $this->record_tag($tag_check);
                $this->tagType = 'SCRIPT';
            }

        } elseif ($tag_check === 'style') {
            if (!$peek) {
                    $this->record_tag($tag_check);
                    $this->tagType = 'STYLE';
            }

        } elseif ($this->is_unformatted($tag_check)) {
            // do not reformat the "unformatted" tags
            $comment = $this->get_unformatted('</' . $tag_check . '>', $tag_complete);
            //...delegate to get_unformatted function
            $content[] = $comment;
            // Preserve collapsed whitespace either before or after this tag.
            if ($tag_start > 0 && in_array($this->input[$tag_start - 1], $this->whitespace)) {
                    array_splice($content, 0, 0, $this->input[$tag_start - 1]);
            }

            $tag_end = $this->pos - 1;
            if (in_array($this->input[$tag_end + 1], $this->whitespace)) {
                $content[] = $this->input[$tag_end + 1];
            }

            $this->tagType = 'SINGLE';

        } elseif ($tag_check && $tag_check[0] === '!') {
            //peek for <! comment
            // for comments content is already correct.
            if (!$peek) {
                    $this->tagType = 'SINGLE';
                    $this->traverse_whitespace();
            }

        } elseif (!$peek) {
            if ($tag_check && $tag_check[0] === '/') { //this tag is a double tag so check for tag-ending
                    $this->retrieve_tag(substr($tag_check, 1)); //remove it and all ancestors
                    $this->tagType = 'END';
                    $this->traverse_whitespace();
            } else { //otherwise it's a start-tag
                $this->record_tag($tag_check); //push it on the tag stack
                if (strtolower($tag_check) !== 'html') {
                    $this->indentContent = true;
                }

                $this->tagType = 'START';

                // Allow preserving of newlines after a start tag
                $this->traverse_whitespace();
            }

            if (in_array($tag_check, $this->extraLiners)) { //check if this double needs an extra line
                $this->print_newline(false, $this->output);
                if (count($this->output) && $this->output[count($this->output) - 2] !== "\n") {
                    $this->print_newline(true, $this->output);
                }
            }

        }

        if ($peek) {
            $this->pos = $orig_pos;
            $this->lineCharCount = $orig_line_char_count;
        }

            return implode('', $content); //returns fully formatted tag
    }

    //function to return comment content in its entirety
    private function get_comment($start_pos)
    {
        // this is will have very poor perf, but will work for now.
        $comment = '';
        $delimiter = '>';
        $matched = false;

        $this->pos = $start_pos;
        $input_char = $this->input[$this->pos];
        $this->pos++;

        while ($this->pos <= $this->inputLength) {
            $comment .= $input_char;

            // only need to check for the delimiter if the last chars match
            if ($comment[strlen($comment) - 1] === $delimiter[strlen($delimiter) - 1]
                && strpos($comment, $delimiter) !== false
            ) {
                break;
            }

            // only need to search for custom delimiter for the first few characters
            if (!$matched && strlen($comment) < 10) {
                if (strpos($comment, '<![if') === 0) {
                    //peek for <![if conditional comment
                    $delimiter = '<![endif]>';
                    $matched = true;
                
                } elseif (strpos($comment, '<![cdata[') === 0) {
                    //if it's a <[cdata[ comment...
                    $delimiter = ']]>';
                    $matched = true;
                
                } elseif (strpos($comment, '<![') === 0) {
                    // some other ![ comment? ...
                    $delimiter = ']>';
                    $matched = true;
                
                } elseif (strpos($comment, '<!--') === 0) {
                    // <!-- comment ...
                    $delimiter = '-->';
                    $matched = true;
                
                }
            }

            $input_char = $this->input[$this->pos];
            $this->pos++;
        }

        return $comment;
    }

    //function to return unformatted content in its entirety
    private function get_unformatted($delimiter, $orig_tag = false)
    {
        if ($orig_tag && strpos(strtolower($orig_tag), (string) $delimiter) !== false) {
            return '';
        }

        $input_char = '';
        $content = '';
        $min_index = 0;

        /**
         * @var boolean
         */
        $space = true;

        do {
            if ($this->pos >= $this->inputLength) {
                return $content;
            }

            $input_char = $this->input[$this->pos];
            $this->pos++;

            if (in_array($input_char, $this->whitespace)) {
                if ($space == false) {
                    $this->lineCharCount--;
                    continue;
                }
                
                if ($input_char === "\n" || $input_char === "\r") {
                    $content .= "\n";
                    /*  Don't change tab indention for unformatted blocks.  If using code for html editing, this will greatly affect <pre> tags if they are specified in the 'unformatted array'
                    for ($i = 0; $i < $this->indent_level; i++) {
                      $content .= $this->indent_string;
                    }
                    $space = false; //...and make sure other indentation is erased
                    */
                    $this->lineCharCount = 0;
                    continue;
                }
            }
            
            $content .= $input_char;
            $this->lineCharCount++;
            $space = true;

            /**
             * Assuming Base64 This method could possibly be applied to All Tags
             * but Base64 doesn't have " or ' as part of its data
             * so it is safe to look for the Next delimiter to find the end of the data
             * instead of reading Each character one at a time.
             */

            if (preg_match('/^data:image\/(bmp|gif|jpeg|png|svg\+xml|tiff|x-icon);base64$/', $content)) {
                $content .= substr($this->input, $this->pos, strpos($this->input, (string) $delimiter, $this->pos) - $this->pos);

                $this->lineCharCount = strpos($this->input, (string) $delimiter, $this->pos) - $this->pos;

                $this->pos = strpos($this->input, (string) $delimiter, $this->pos);

                continue;
            }
        } while (strpos(strtolower($content), (string) $delimiter, $min_index) === false);

        return $content;
    }

    //initial handler for token-retrieval
    private function get_token()
    {
        if ($this->lastToken === 'TK_TAG_SCRIPT' || $this->lastToken === 'TK_TAG_STYLE') { //check if we need to format javascript
            $type = substr($this->lastToken, 7);
            $token = $this->get_contents_to($type);
            if (!is_string($token)) {
                return $token;
            }
            
            return [$token, 'TK_' . $type];
        }
        
        if ($this->currentMode === 'CONTENT') {
            $token = $this->get_content();

            if (!is_string($token)) {
                return $token;
            }

            return [$token, 'TK_CONTENT'];
        }

        if ($this->currentMode === 'TAG') {
            $token = $this->get_tag();

            if (!is_string($token)) {
                return $token;
            }

            $tag_name_type = 'TK_TAG_' . $this->tagType;
            return [$token, $tag_name_type];
        }

        return null;
    }

    private function get_full_indent(int $level): string
    {
        $level = $this->indentLevel + $level;
        if ($level < 1) {
            return '';
        }

        return str_repeat($this->indentString, $level);
    }

    private function is_unformatted($tag_check)
    {
        //is this an HTML5 block-level link?
        if (!in_array($tag_check, $this->options['unformatted'])) {
            return false;
        }

        if (strtolower($tag_check) !== 'a' || !in_array('a', $this->options['unformatted'])) {
            return true;
        }

        //at this point we have an  tag; is its first child something we want to remain unformatted?
        $next_tag = $this->get_tag(true /* peek. */);

        // test next_tag to see if it is just html tag (no external content)
        $matches = [];
        preg_match('/^\s*<\s*\/?([a-z]*)\s*[^>]*>\s*$/', ($next_tag ?: ""), $matches);
        $tag = $matches !== [] ? $matches : null;

        // if next_tag comes back but is not an isolated tag, then
        // let's treat the 'a' tag as having content
        // and respect the unformatted option
        return $tag === null || $tag === [] || in_array($tag, $this->options['unformatted']);
    }

    private function print_newline($force, &$arr)
    {
        $this->lineCharCount = 0;
        if (!$arr || count($arr) === 0) {
            return;
        }
        
        if ($force || ($arr[count($arr) - 1] !== "\n")) { //we might want the extra line
            $arr[] = "\n";
        }
    }

    private function print_indentation(&$arr)
    {
        for ($i = 0; $i < $this->indentLevel; $i++) {
            $arr[] = $this->indentString;
            $this->lineCharCount += strlen($this->indentString);
        }
    }

    private function print_token($text)
    {
        if (($text || $text !== '') && (count($this->output) && $this->output[count($this->output) - 1] === "\n")) {
            $this->print_indentation($this->output);
            $text = ltrim($text);
        
        }
        
        $this->print_token_raw($text);
    }

    private function print_token_raw($text)
    {
        if ($text && $text !== '') {
            if (strlen($text) > 1 && $text[strlen($text) - 1] === "\n") {
                // unformatted tags can grab newlines as their last character
                $this->output[] = substr($text, 0, -1);
                $this->print_newline(false, $this->output);
            } else {
                $this->output[] = $text;
            }
        }

        for ($n = 0; $n < $this->newlines; $n++) {
            $this->print_newline($n > 0, $this->output);
        }
        
        $this->newlines = 0;
    }

    private function indent()
    {
        $this->indentLevel++;
    }

    public function beautify($input)
    {
        $this->input = $input; //gets the input for the Parser
        $this->inputLength = strlen($this->input);
        $this->output = [];

        while (true) {
            $t = $this->get_token();

            $this->tokenText = $t[0];
            $this->tokenType = $t[1];

            if ($this->tokenType === 'TK_EOF') {
                break;
            }

            switch ($this->tokenType) {
            case 'TK_TAG_START':
                $this->print_newline(false, $this->output);
                $this->print_token($this->tokenText);
                if ($this->indentContent) {
                    $this->indent();
                    $this->indentContent = false;
                }
                
                $this->currentMode = 'CONTENT';
                break;
            case 'TK_TAG_STYLE':
            case 'TK_TAG_SCRIPT':
                $this->print_newline(false, $this->output);
                $this->print_token($this->tokenText);
                $this->currentMode = 'CONTENT';
                break;
            case 'TK_TAG_END':
                //Print new line only if the tag has no content and has child
                if ($this->lastToken === 'TK_CONTENT' && $this->lastText === '') {
                    $matches = [];
                    preg_match('/\w+/', $this->tokenText, $matches);
                    $tag_name = $matches[0] ?? null;

                    $tag_extracted_from_last_output = null;
                    if (count($this->output) != 0) {
                        $matches = [];
                        preg_match('/(?:<|{{#)\s*(\w+)/', $this->output[count($this->output) - 1], $matches);
                        $tag_extracted_from_last_output = $matches[0] ?? null;
                    }
                    
                    if (is_null($tag_extracted_from_last_output) || $tag_extracted_from_last_output[1] !== $tag_name) {
                        $this->print_newline(false, $this->output);
                    }
                }
                
                $this->print_token($this->tokenText);
                $this->currentMode = 'CONTENT';
                break;
            case 'TK_TAG_SINGLE':
                // Don't add a newline before elements that should remain unformatted.
                $matches = [];
                preg_match('/^\s*<([a-z]+)/i', $this->tokenText, $matches);
                $tag_check = $matches !== [] ? $matches : null;

                if ($tag_check === null || $tag_check === [] || !in_array($tag_check[1], $this->options['unformatted'])) {
                    $this->print_newline(false, $this->output);
                }
                
                $this->print_token($this->tokenText);
                $this->currentMode = 'CONTENT';
                break;
            case 'TK_CONTENT':
                $this->print_token($this->tokenText);
                $this->currentMode = 'TAG';
                break;
            case 'TK_STYLE':
            case 'TK_SCRIPT':
                if ($this->tokenText !== '') {
                    $this->print_newline(false, $this->output);
                    $text = $this->tokenText;
                    $_beautifier = false;
                    $script_indent_level = 1;

                    if ($this->tokenType === 'TK_SCRIPT') {
                        $_beautifier = $this->jsBeautify;
                    
                    } elseif ($this->tokenType === 'TK_STYLE') {
                        $_beautifier = $this->cssBeautify;
                    
                    }

                    if ($this->options['indent_scripts'] === "keep") {
                        $script_indent_level = 0;
                    
                    } elseif ($this->options['indent_scripts'] === "separate") {
                        $script_indent_level = -$this->indentLevel;
                    
                    }

                    $indentation = $this->get_full_indent($script_indent_level);
                    if ($_beautifier) {
                        // call the Beautifier if avaliable
                        $text = $_beautifier(preg_replace('/^\s*/', $indentation, $text), $this->options);
                    } else {
                        // simply indent the string otherwise

                        $matches = [];
                        preg_match('/^\s*/', $text, $matches);
                        $white = $matches[0] ?? null;

                        $matches = [];
                        preg_match('/[^\n\r]*$/', $white, $matches);
                        $dummy = $matches[0] ?? null;

                        $_level = count(explode($this->indentString, $dummy)) - 1;
                        $reindent = $this->get_full_indent($script_indent_level - $_level);

                        $text = preg_replace('/^\s*/', $indentation, $text);
                        $text = preg_replace(
                            '/\r\n|\r|\n/',
                            "\n" . $reindent,
                            $text
                        );
                        $text = preg_replace('/\s+$/', '', $text);
                    }

                    if ($text) {
                        $this->print_token_raw($indentation . trim($text));
                        $this->print_newline(false, $this->output);
                    }
                }
                
                $this->currentMode = 'TAG';
                break;
            }

            $this->lastToken = $this->tokenType;
            $this->lastText = $this->tokenText;
        }

        return implode('', $this->output);
    }
}
