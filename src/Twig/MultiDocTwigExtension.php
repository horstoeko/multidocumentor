<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Twig;

use DOMDocument;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use League\CommonMark\CommonMarkConverter;

/**
 * Class for multiDoc twig extensions
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocTwigExtension extends AbstractExtension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter("removeinvisible", [$this, 'removeInvisibleCharacters'], ['is_safe' => ['html']]),
            new TwigFilter("parsedown", [$this, 'parsedown'], ['is_safe' => ['html']]),
            new TwigFilter("parsedownnop", [$this, 'parsedownNoParagrap'], ['is_safe' => ['html']]),
            new TwigFilter("stripoutertag", [$this, 'stripoutertag'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('downloadurl', [$this, 'downloadUrl']),
        ];
    }

    /**
     * Removes invisble Characters
     *
     * @param  string $string
     * @return string
     */
    public function removeInvisibleCharacters($string): string
    {
        return preg_replace('/[\x00-\x1F\x7F]/', '', $string);
    }

    /**
     * Parse markdown to HTML
     *
     * @param  string $markDownString
     * @return string
     */
    public function parsedown($markDownString): string
    {
        $converter = new CommonMarkConverter(
            [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]
        );

        $htmlString = $converter->convertToHtml($markDownString ?? "");

        return $htmlString;
    }

    /**
     * Parse markdown to HTML. The wrapping paragraph will be removed
     *
     * @param  string $markDownString
     * @return string
     */
    public function parsedownNoParagrap($markDownString): string
    {
        $htmlString = $this->parsedown($markDownString ?? "");
        $htmlString = preg_replace('!^<p>(.*?)</p>$!i', '$1', $htmlString);

        return $htmlString;
    }

    /**
     * Strip outermost specific tag
     *
     * @param  string $markupString
     * @param  string $tagName
     * @return string
     */
    public function stripoutertag($htmlString, $tagName): string
    {
        if (trim($htmlString) == "") {
            return $htmlString;
        }

        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);

        $elements = $doc->getElementsByTagName($tagName);

        if (count($elements) == 0) {
            return $htmlString;
        }

        $htmlString = "";

        foreach ($elements[0]->childNodes as $child) {
            $htmlString .= $elements[0]->ownerDocument->saveHTML($child);
        }

        return $htmlString;
    }

    /**
     * Downloads content from URL and returns the output
     *
     * @param  string $url
     * @return void
     */
    public function downloadUrl(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
