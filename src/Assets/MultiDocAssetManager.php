<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Assets;

/**
 * Class representing the Asset Manager
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocAssetManager
{
    /**
     * Get the root directory of the assets
     *
     * @return string
     */
    public static function getAssetsDirectory(): string
    {
        return rtrim(dirname(__FILE__), DIRECTORY_SEPARATOR);
    }

    /**
     * Return the HTML directory
     *
     * @return string
     */
    public static function getHtmlDirectory(): string
    {
        return self::getAssetsDirectory() . DIRECTORY_SEPARATOR . "Html";
    }

    /**
     * Return the MarkDown directory
     *
     * @return string
     */
    public static function getMarkDownDirectory(): string
    {
        return self::getAssetsDirectory() . DIRECTORY_SEPARATOR . "MarkDown";
    }

    /**
     * Return the directory where fonts are located
     *
     * @return string
     */
    public static function getFontsDirectory(): string
    {
        return self::getAssetsDirectory() . DIRECTORY_SEPARATOR . "Fonts";
    }
}
