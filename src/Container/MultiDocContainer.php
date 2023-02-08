<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Container;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class representing the MultiDoc Container
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 *
 * @property array $includeDirectories
 * @property array $excludeDirectories
 * @property string $outputTo
 * @property string $outputFormat
 * @property string $assetDirectory
 * @property string $htmlDirectory
 * @property string $markdownDirectory
 * @property string $fontsDirectory
 * @property array $fontsSettings
 * @property string $fontDefault
 * @property array $customRenderers
 * @property \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
 *
 * @method array getIncludeDirectories()
 * @method array getExcludeDirectories()
 * @method string getOutputTo()
 * @method string getOutputFormat()
 * @method string getAssetDirectory()
 * @method string getHtmlDirectory()
 * @method string getMarkdownDirectory()
 * @method string getFontsDirectory()
 * @method array getFontsSettings()
 * @method string getFontDefault()
 * @method array getCustomRenderers()
 * @method \Symfony\Component\EventDispatcher\EventDispatcher getEventDispatcher()
 *
 * @method void setIncludeDirectories(array $includeDirectories)
 * @method void setExcludeDirectories(array $excludeDirectories)
 * @method void setOutputTo(string $outputTo)
 * @method void setOutputFormat(string $outputFormat)
 * @method void setAssetDirectory(string $assetDirectory)
 * @method void setHtmlDirectory(string $htmlDirectory)
 * @method void setMarkdownDirectory(string $markdownDirectory)
 * @method void setFontsDirectory(string $fontsDirectory)
 * @method void setFontsSettings(array $fontSettings)
 * @method void setFontDefault(string $fondDefault)
 * @method void setCustomRenderers(array $renderers)
 * @method void setEventDispatcher(\Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher)
 *
 * @method void addFontsSettings(string $fontName, string $fontType, string $fontFile)
 */
class MultiDocContainer
{
    /**
     * The container that holds all settings
     *
     * @var array
     */
    public $container = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->includeDirectories = [];
        $this->excludeDirectories = [];
        $this->outputTo = "";
        $this->outputFormat = "";
        $this->assetDirectory = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Assets");
        $this->htmlDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "Html");
        $this->markdownDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "MarkDown");
        $this->fontsDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "Fonts");
        $this->fontsSettings = [];
        $this->fontDefault = "";
        $this->customRenderers = [];
        $this->eventDispatcher = new EventDispatcher;
    }

    /**
     * Magic getter
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return (isset($this->container[$name])) ? $this->container[$name] : null;
    }

    /**
     * Magic setter
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->container[$name] = $value;
    }

    /**
     * Magic call
     *
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $containerItemName = lcFirst(substr($name, 3));

        if (strcasecmp(substr($name, 0, 3), "get") === 0) {
            return isset($this->container[$containerItemName]) ? $this->container[$containerItemName] : null;
        }
        if (strcasecmp(substr($name, 0, 3), "set") === 0 && count($arguments) > 0) {
            $this->container[$containerItemName] = $arguments[0];
            return;
        }
        if (strcasecmp($name, "addFontsSettings") === 0) {
            $this->container[$containerItemName][$arguments[0]][$arguments[1]] = $arguments[2];
            return;
        }
        if (strcasecmp(substr($name, 0, 3), "add") === 0 && count($arguments) > 0) {
            $containerItemName = lcFirst(substr($name, 3));
            $this->container[$containerItemName] = isset($this->container[$containerItemName]) && is_array($this->container[$containerItemName]) ? $this->container[$containerItemName] : [];
            $this->container[$containerItemName][] = $arguments;
            return;
        }
    }
}
