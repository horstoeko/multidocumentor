<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Config;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Class representing the MultiDoc Configuration
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocConfig extends ConsoleApplication
{
    /**
     * Directories to include
     *
     * @var string[]
     */
    protected $includeDirectories = [];

    /**
     * Directories to exclude
     *
     * @var string[]
     */
    protected $excludeDirectories = [];

    /**
     * Directory to which the docs should be published
     *
     * @var string
     */
    protected $outputTo = "";

    /**
     * The output format
     *
     * @var string
     */
    protected $outputFormat = "";

    /**
     * The directory where the assets are stored
     *
     * @var string
     */
    protected $assetDirectory = "";

    /**
     * The directory where the HTML markup files are stored
     *
     * @var string
     */
    protected $htmlDirectory = "";

    /**
     * The directory where the markdown markup files are stored
     *
     * @var string
     */
    protected $markdownDirectory = "";

    /**
     * The directory where the font files are stored
     *
     * @var string
     */
    protected $fontsDirectory = "";

    /**
     * Settings for several fonts (see FontsDirectory)
     *
     * @var array
     * @see https://mpdf.github.io/fonts-languages/fonts-in-mpdf-7-x.html
     */
    protected $fontsSettings = [];

    /**
     * The default font
     *
     * @var string
     */
    protected $fontDefault = "";

    /**
     * List of custom renderers
     *
     * @var string[]
     */
    protected $customRenderers = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assetDirectory = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "Assets");
        $this->htmlDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "Html");
        $this->markdownDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "MarkDown");
        $this->fontsDirectory = realpath($this->assetDirectory . DIRECTORY_SEPARATOR . "Fonts");
    }

    /**
     * Get the directories to search in
     *
     * @return array
     */
    public function getIncludeDirectories(): array
    {
        return $this->includeDirectories;
    }

    /**
     * Set a bunch of directories to search in
     *
     * @param  array $directories
     * @return \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    public function setIncludeDirectories(array $directories): MultiDocConfig
    {
        $this->includeDirectories = $directories;
        return $this;
    }

    /**
     * Add a directory to search in
     *
     * @param  string $directory
     * @return \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    public function addIncludeDirectory(string $directory): MultiDocConfig
    {
        $this->includeDirectories[] = $directory;
        return $this;
    }

    /**
     * Get the directories to exclude from search
     *
     * @return array
     */
    public function getExcludeDirectories(): array
    {
        return $this->excludeDirectories;
    }

    /**
     * Set a bunch of directories to exclude from search
     *
     * @param  array $directories
     * @return \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    public function setExcludeDirectories(array $directories): MultiDocConfig
    {
        $this->excludeDirectories = $directories;
        return $this;
    }

    /**
     * Add a directory to exclude from search
     *
     * @param  string $directory
     * @return \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    public function addExcludeDirectory(string $directory): MultiDocConfig
    {
        $this->excludeDirectories[] = $directory;
        return $this;
    }

    /**
     * Get the directory to which the documentation is saved
     *
     * @return string
     */
    public function getOutputTo(): string
    {
        return $this->outputTo;
    }

    /**
     * Set the directory to which the documentation is saved
     *
     * @return \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    public function setOutputTo(string $outputTo): MultiDocConfig
    {
        $this->outputTo = $outputTo;
        return $this;
    }

    /**
     * Get the format in which the documentation is rendered
     *
     * @return string
     */
    public function getOutputFormat(): string
    {
        return $this->outputFormat;
    }

    /**
     * Set the directory to which the documentation is saved
     *
     * @param  string $outputFormat
     * @return MultiDocConfig
     */
    public function setOutputFormat(string $outputFormat): MultiDocConfig
    {
        $this->outputFormat = $outputFormat;
        return $this;
    }

    /**
     * Get the directory where the assets are stored
     *
     * @return string
     */
    public function getAssetDirectory(): string
    {
        return $this->assetDirectory;
    }

    /**
     * Set the directory where the assets are stored
     *
     * @param  string $assetDirectory
     * @return MultiDocConfig
     */
    public function setAssetDirectory(string $assetDirectory): MultiDocConfig
    {
        $this->assetDirectory = $assetDirectory;
        return $this;
    }

    /**
     * Get the directory where the html markup files are stored
     *
     * @return string
     */
    public function getHtmlDirectory(): string
    {
        return $this->htmlDirectory;
    }

    /**
     * Set the directory where the html markup files are stored
     *
     * @param  string $htmlDirectory
     * @return MultiDocConfig
     */
    public function setHtmlDirectory(string $htmlDirectory): MultiDocConfig
    {
        $this->htmlDirectory = $htmlDirectory;
        return $this;
    }

    /**
     * Get the directory where the markdown markup files are stored
     *
     * @return string
     */
    public function getMarkdownDirectory(): string
    {
        return $this->markdownDirectory;
    }

    /**
     * Set the directory where the markdown markup files are stored
     *
     * @param  string $markdownDirectory
     * @return MultiDocConfig
     */
    public function setMarkdownDirectory(string $markdownDirectory): MultiDocConfig
    {
        $this->markdownDirectory = $markdownDirectory;
        return $this;
    }

    /**
     * Get the directory where the font files are stored
     *
     * @return string
     */
    public function getFontsDirectory(): string
    {
        return $this->fontsDirectory;
    }

    /**
     * Set the directory where the font files are stored
     *
     * @param  string $fontsDirectory
     * @return MultiDocConfig
     */
    public function setFontsDirectory(string $fontsDirectory): MultiDocConfig
    {
        $this->fontsDirectory = $fontsDirectory;
        return $this;
    }

    /**
     * Get the font settings
     *
     * @return array
     */
    public function getFontsSettings(): array
    {
        return $this->fontsSettings;
    }

    /**
     * Set the font settings
     *
     * @param  array $fontsSettings
     * @return MultiDocConfig
     */
    public function setFontsSettings(array $fontsSettings): MultiDocConfig
    {
        $this->fontsSettings = $fontsSettings;
        return $this;
    }

    /**
     * Add a font config
     *
     * @param  string $name
     * @param  string $type
     * @param  string $filename
     * @return MultiDocConfig
     */
    public function addFontsSettings(string $name, string $type, string $filename): MultiDocConfig
    {
        $this->fontsSettings[$name][$type] = $filename;
        return $this;
    }

    /**
     * Get the default font
     *
     * @return string
     */
    public function getFontDefault(): string
    {
        return $this->fontDefault;
    }

    /**
     * Set the default font
     *
     * @param  string $fontDefault
     * @return MultiDocConfig
     */
    public function setFontDefault(string $fontDefault): MultiDocConfig
    {
        $this->fontDefault = $fontDefault;
        return $this;
    }

    /**
     * Returns a list of all custom renderers
     *
     * @return string[]
     */
    public function getCustomRenderers(): array
    {
        return $this->customRenderers;
    }

    /**
     * Set a list of custom renderers
     *
     * @param  string[] $customRenderers
     * @return MultiDocConfig
     */
    public function setCustomRenderer(array $customRenderers): MultiDocConfig
    {
        $this->customRenderers = $customRenderers;
        return $this;
    }

    /**
     * Add a custom renderer
     *
     * @param  string $customRenderer
     * @return MultiDocConfig
     */
    public function addCustomRenderer(string $customRenderer): MultiDocConfig
    {
        $this->customRenderers[] = $customRenderer;
        return $this;
    }
}
