<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for the service which will give us all files
 * to handle
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocFinderServiceInterface
{
    /**
     * Set the entry point(s) where to start to look for files
     *
     * @param string|array $directory
     * @return MultiDocFinderServiceInterface
     */
    public function include($directory): MultiDocFinderServiceInterface;

    /**
     * Set the directories which should excluded from the search
     *
     * @param string|array $directory
     * @return MultiDocFinderServiceInterface
     */
    public function exclude($directory): MultiDocFinderServiceInterface;

    /**
     * Get all found files
     *
     * @return array
     */
    public function files(): array;

    /**
     * Get all files as a PHPdoc LocalFile
     *
     * @return \phpDocumentor\Reflection\File\LocalFile[]
     */
    public function filesAsPhpDocLocalFiles(): array;
}