<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocFinderServiceInterface;
use phpDocumentor\Reflection\File\LocalFile;
use Symfony\Component\Finder\Finder;

/**
 * Service class which will give us all files to handle
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocFinderService implements MultiDocFinderServiceInterface
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * Internal finder component
     *
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * Constructor
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;

        $this->finder = new Finder();
        $this->finder->ignoreUnreadableDirs();
        $this->finder->ignoreVCS(true);
        $this->finder->ignoreVCSIgnored(true);
        $this->finder->sortByName();

        $this->finder->in($this->config->getIncludeDirectories())->exclude($this->config->getExcludeDirectories());
    }

    /**
     * @inheritDoc
     */
    public function getAllFiles(): array
    {
        $files = array_values(array_map(function ($file) {
            return $file->getRealPath();
        }, iterator_to_array($this->finder->files()->name('*.php'))));

        return $files;
    }

    /**
     * @inheritDoc
     */
    public function getAllFilesAsPhpDocLocalFiles(): array
    {
        $files = array_map(function ($file) {
            return new LocalFile($file);
        }, array_values(array_map(function ($file) {
            return $file->getRealPath();
        }, iterator_to_array($this->finder->files()->name('*.php')))));

        return $files;
    }
}
