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
     * @param  \ArrayAccess|array $array
     * @param  string|int         $key
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
     * @param [type] $obj
     * @return void
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
}
