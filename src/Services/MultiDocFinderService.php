<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Container\MultiDocContainer;
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
     * Container (Settings)
     *
     * @var \horstoeko\multidocumentor\Container\MultiDocContainer
     */
    protected $container;

    /**
     * Internal finder component
     *
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * Constructor
     */
    public function __construct(MultiDocContainer $container)
    {
        $this->container = $container;

        $this->finder = new Finder();
        $this->finder->ignoreUnreadableDirs();
        $this->finder->ignoreVCS(true);
        $this->finder->ignoreVCSIgnored(true);
        $this->finder->sortByName();

        $this->finder->in($this->container->getIncludeDirectories())->exclude($this->container->getExcludeDirectories());
    }

    /**
     * @inheritDoc
     */
    public function getAllFiles(): array
    {
        return array_values(
            array_map(
                function ($file) {
                    return $file->getRealPath();
                },
                iterator_to_array($this->finder->files()->name('*.php'))
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getAllFilesAsPhpDocLocalFiles(): array
    {
        return array_map(
            function ($file) {
                return new LocalFile($file);
            },
            array_values(
                array_map(
                    function ($file) {
                        return $file->getRealPath();
                    },
                    iterator_to_array($this->finder->files()->name('*.php'))
                )
            )
        );
    }
}
