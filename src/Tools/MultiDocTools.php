<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Tools;

use Closure;
use ArrayAccess;
use ReflectionClass;
use horstoeko\multidocumentor\Tools\MultiDocHtmlBeautifier;

/**
 * Class representing a collection of tools
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocTools
{
    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function isArray($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  array      $array
     * @param  string|int $key
     * @return bool
     */
    public static function arrayKeyExists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  iterable $array
     * @return array
     */
    public static function arrayCollapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @param  array ...$args
     * @return mixed
     */
    public static function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed                 $target
     * @param  string|array|int|null $key
     * @param  mixed                 $default
     * @return mixed
     */
    public static function dataGet($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {
                if (!is_array($target)) {
                    return self::value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = self::dataGet($item, $key);
                }

                return in_array('*', $key) ? self::arrayCollapse($result) : $result;
            }

            if (self::isArray($target) && self::arrayKeyExists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return self::value($default);
            }
        }

        return $target;
    }

    /**
     * Convert an object to an associate array
     *
     * @param  object $obj
     * @return mixed
     */
    public static function objectToArray($obj)
    {
        if (is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach ($ret as &$item) {
                $item = self::objectToArray($item);
            }
            return $ret;
        } else {
            return $obj;
        }
    }

    /**
     * Checks if a class implementes an interface
     *
     * @param  string $className
     * The class identified by it's class name
     * @param  string $interface
     * The inteface
     * @return boolean
     */
    public static function classImplementsInterface(string $className, string $interface): bool
    {
        return (new ReflectionClass($className))->implementsInterface($interface);
    }

    /**
     * Beautifies a HTML string
     *
     * @param  string $html
     * @return string
     */
    public static function beautifyHtml(string $html): string
    {
        $htmlBeautifier = new MultiDocHtmlBeautifier(
            [
            'indent_inner_html' => false,
            'indent_char' => " ",
            'indent_size' => 4,
            'wrap_line_length' => 32786,
            'unformatted' => ['code', 'pre'],
            'preserve_newlines' => false,
            'max_preserve_newlines' => 32786,
            'indent_scripts' => 'normal',
            ]
        );

        return $htmlBeautifier->beautify($html);
    }

    /**
     * Minify a HTML string
     *
     * @param  string $html
     * @return string
     */
    public static function minifyHtml(string $html): string
    {
        $replace = array(
            //remove tabs before and after HTML tags
            '/\>[^\S ]+/s'   => '>',
            '/[^\S ]+\</s'   => '<',
            //shorten multiple whitespace sequences; keep new-line characters because they matter in JS!!!
            '/([\t ])+/s'  => ' ',
            //remove leading and trailing spaces
            '/^([\t ])+/m' => '',
            '/([\t ])+$/m' => '',
            // remove JS line comments (simple only); do NOT remove lines containing URL (e.g. 'src="http://server.com/"')!!!
            '~//[a-zA-Z0-9 ]+$~m' => '',
            //remove empty lines (sequence of line-end and white-space characters)
            '/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
            //remove empty lines (between HTML tags); cannot remove just any line-end characters because in inline JS they can matter!
            '/\>[\r\n\t ]+\</s'    => '><',
            //remove "empty" lines containing only JS's block end character; join with next line (e.g. "}\n}\n</script>" --> "}}</script>"
            '/}[\r\n\t ]+/s'  => '}',
            '/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
            //remove new-line after JS's function or condition start; join with next line
            '/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
            '/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
            //remove new-line after JS's line end (only most obvious and safe cases)
            '/\),[\r\n\t ]+/s'  => '),',
            //remove quotes from HTML attributes that does not contain spaces; keep quotes around URLs!
            '~([\r\n\t ])?([a-zA-Z0-9]+)="([a-zA-Z0-9_/\\-]+)"([\r\n\t ])?~s' => '$1$2=$3$4', //$1 and $4 insert first white-space character found before/after attribute
        );

        return preg_replace(array_keys($replace), array_values($replace), $html);
    }
}
