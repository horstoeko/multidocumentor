<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for the service which will create the documentation
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocCreatorServiceInterface
{
    /**
     * Set the entry point(s) where to start to look for files
     *
     * @param string|array $directory
     * @return MultiDocCreatorServiceInterface
     */
    public function addDirectoryToInclude($directory): MultiDocCreatorServiceInterface;

    /**
     * Set the directories which should excluded from the search
     *
     * @param string|array $directory
     * @return MultiDocCreatorServiceInterface
     */
    public function addDirectoryToExclude($directory): MultiDocCreatorServiceInterface;

    /**
     * Set the directory to which the docs should be published
     *
     * @param string $outputTo
     * @return MultiDocCreatorServiceInterface
     */
    public function setOutputTo(string $outputTo): MultiDocCreatorServiceInterface;

    /**
     * Set the output format
     *
     * @param integer $format
     * @return MultiDocCreatorServiceInterface
     */
    public function setOutputFormat(int $format): MultiDocCreatorServiceInterface;

    /**
     * Starts the creation of the documentation
     *
     * @return MultiDocCreatorServiceInterface
     */
    public function process(): MultiDocCreatorServiceInterface;
}