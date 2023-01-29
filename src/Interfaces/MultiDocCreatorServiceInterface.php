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
    public function include($directory): MultiDocCreatorServiceInterface;

    /**
     * Set the directories which should excluded from the search
     *
     * @param string|array $directory
     * @return MultiDocCreatorServiceInterface
     */
    public function exclude($directory): MultiDocCreatorServiceInterface;

    /**
     * Set the directory to which the docs should be published
     *
     * @param string $outputTo
     * @return MultiDocCreatorServiceInterface
     */
    public function outputTo(string $outputTo): MultiDocCreatorServiceInterface;

    /**
     * Set the output format
     *
     * @param integer $format
     * @return MultiDocCreatorServiceInterface
     */
    public function format(int $format): MultiDocCreatorServiceInterface;

    /**
     * Starts the creation of the documentation
     *
     * @return MultiDocCreatorServiceInterface
     */
    public function process(): MultiDocCreatorServiceInterface;
}