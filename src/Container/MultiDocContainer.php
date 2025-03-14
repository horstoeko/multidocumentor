<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Container;

use horstoeko\multidocumentor\Events\MultiDocEventDispatcher;

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
 * @property string $cssFilename
 * @property array $customHtmlDirectories
 * @property string $markdownDirectory
 * @property array $customMarkdownDirectories
 * @property string $fontsDirectory
 * @property array $fontsSettings
 * @property string $fontDefault
 * @property array $customRenderers
 * @property boolean $beautifyHtmlOutput
 * @property boolean $minifyHtmlOutput
 * @property \horstoeko\multidocumentor\Events\MultiDocEventDispatcher $eventDispatcher
 * @property string $pageHeader
 * @property string $pageFooter
 *
 * @method array getIncludeDirectories()
 * @method array getExcludeDirectories()
 * @method string getOutputTo()
 * @method string getOutputFormat()
 * @method string getAssetDirectory()
 * @method string getHtmlDirectory()
 * @method string getCssFilename()
 * @method array getCustomHtmlDirectories()
 * @method string getMarkdownDirectory()
 * @method array getCustomMarkdownDirectories()
 * @method string getFontsDirectory()
 * @method array getFontsSettings()
 * @method string getFontDefault()
 * @method array getCustomRenderers()
 * @method booleab getBeautifyHtmlOutput()
 * @method booleab getMinifyHtmlOutput()
 * @method \horstoeko\multidocumentor\Events\MultiDocEventDispatcher getEventDispatcher()
 * @method string getPageHeader()
 * @method string getPageFooter()
 *
 * @method void setIncludeDirectories(array $includeDirectories)
 * @method void setExcludeDirectories(array $excludeDirectories)
 * @method void setOutputTo(string $outputTo)
 * @method void setOutputFormat(string $outputFormat)
 * @method void setAssetDirectory(string $assetDirectory)
 * @method void setHtmlDirectory(string $htmlDirectory)
 * @method void setCssFilename(string $cssFilename)
 * @method void setCustomHtmlDirectories(array $htmlDirectories)
 * @method void setMarkdownDirectory(string $markdownDirectory)
 * @method void setCustomMarkdownDirectories(array $markdownDirectories)
 * @method void setFontsDirectory(string $fontsDirectory)
 * @method void setFontsSettings(array $fontSettings)
 * @method void setFontDefault(string $fondDefault)
 * @method void setCustomRenderers(array $renderers)
 * @method void setBeautifyHtmlOutput(bool $beautifyHtmlOutput)
 * @method void setMinifyHtmlOutput(bool $minifyHtmlOutput)
 * @method void setEventDispatcher(\horstoeko\multidocumentor\Events\MultiDocEventDispatcher $eventDispatcher)
 * @method void setPageHeader()
 * @method void setPageFooter()
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
        $this->initDefaults();
    }

    /**
     * Initializes the default values
     *
     * @return void
     */
    private function initDefaults(): void
    {
        $this->includeDirectories = [];
        $this->excludeDirectories = [];
        $this->outputTo = "";
        $this->outputFormat = "";
        $this->assetDirectory = realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Assets");
        $this->htmlDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "Html");
        $this->cssFilename = $this->htmlDirectory . DIRECTORY_SEPARATOR . "styles.css";
        $this->markdownDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "MarkDown");
        $this->fontsDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "Fonts");
        $this->fontsSettings = [];
        $this->fontDefault = "";
        $this->customRenderers = [];
        $this->customHtmlDirectories = [];
        $this->customMarkdownDirectories = [];
        $this->beautifyHtmlOutput = false;
        $this->minifyHtmlOutput = true;
        $this->eventDispatcher = new MultiDocEventDispatcher;
        $this->pageHeader = "";
        $this->pageFooter = "{PAGENO}/{nbpg}";
    }

    /**
     * Magic getter
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        $containerItemName = lcfirst($name);

        return $this->container[$containerItemName] ?? null;
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
        $containerItemName = lcfirst($name);

        $this->container[$containerItemName] = $value;
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
            return $this->container[$containerItemName] ?? null;
        }
        
        if (strcasecmp(substr($name, 0, 3), "set") === 0 && $arguments !== []) {
            $this->container[$containerItemName] = $arguments[0];
            return null;
        }
        
        if (strcasecmp($name, "addFontsSettings") === 0) {
            $this->container[$containerItemName][$arguments[0]][$arguments[1]] = $arguments[2];
            return null;
        }
        
        if (strcasecmp(substr($name, 0, 3), "add") === 0 && $arguments !== []) {
            $this->container[$containerItemName] = isset($this->container[$containerItemName]) && is_array($this->container[$containerItemName]) ? $this->container[$containerItemName] : [];
            $this->container[$containerItemName][] = $arguments;
            return null;
        }

        return null;
    }
}
