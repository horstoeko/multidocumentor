<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use Symfony\Component\Finder\Finder;
use phpDocumentor\Reflection\File\LocalFile;
use horstoeko\multidocumentor\Interfaces\MultiDocFinderServiceInterface;

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
     * Internal finder component
     *
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->finder = new Finder();
        $this->finder->ignoreUnreadableDirs();
        $this->finder->ignoreVCS(true);
        $this->finder->ignoreVCSIgnored(true);
        $this->finder->sortByName();
    }

    /**
     * @inheritDoc
     */
    public function include($directory): MultiDocFinderServiceInterface
    {
        $this->finder->in($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exclude($directory): MultiDocFinderServiceInterface
    {
        $this->finder->exclude($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function files(): array
    {
        $files = array_values(array_map(function ($file) {
            return $file->getRealPath();
        }, iterator_to_array($this->finder->files('*.php'))));

        return $files;
    }

    /**
     * @inheritDoc
     */
    public function filesAsPhpDocLocalFiles(): array
    {
        $files = array_map(function ($file) {
            return new LocalFile($file);
        }, array_values(array_map(function ($file) {
            return $file->getRealPath();
        }, iterator_to_array($this->finder->files('*.php')))));

        return $files;
    }
}
