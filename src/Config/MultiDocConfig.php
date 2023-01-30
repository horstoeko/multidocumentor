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
     * @var integer
     */
    protected $outputFormat = 0;

    public function __construct()
    {
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
     * @param array $directories
     * @return MultiDocConfig
     */
    public function setIncludeDirectories(array $directories): MultiDocConfig
    {
        $this->includeDirectories = $directories;
        return $this;
    }

    /**
     * Add a directory to search in
     *
     * @param string $directory
     * @return MultiDocConfig
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
    public function getExclideDirectories(): array
    {
        return $this->excludeDirectories;
    }

    /**
     * Set a bunch of directories to exclude from search
     *
     * @param array $directories
     * @return MultiDocConfig
     */
    public function setExcludeDirectories(array $directories): MultiDocConfig
    {
        $this->excludeDirectories = $directories;
        return $this;
    }

    /**
     * Add a directory to exclude from search
     *
     * @param string $directory
     * @return MultiDocConfig
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
     * @return string
     */
    public function setOutputTo(string $outputTo): MultiDocConfig
    {
        $this->outputTo = $outputTo;
        return $this;
    }

    /**
     * Get the format in which the documentation is rendered
     *
     * @return int
     */
    public function getOutputFormat(): int
    {
        return $this->outputFormat;
    }

    /**
     * Set the directory to which the documentation is saved
     *
     * @return string
     */
    public function setOutputFormat(int $outputFormat): MultiDocConfig
    {
        $this->outputFormat = $outputFormat;
        return $this;
    }
}