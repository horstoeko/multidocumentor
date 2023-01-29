<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for the service which will render the documentation
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocRenderServiceInterface
{
    /**
     * Set the directory to which the docs should be published
     *
     * @param string $outputTo
     * @return MultiDocRenderServiceInterface
     */
    public function outputTo(string $outputTo): MultiDocRenderServiceInterface;

    /**
     * Set the output format
     *
     * @param integer $format
     * @return MultiDocRenderServiceInterface
     */
    public function format(int $format): MultiDocRenderServiceInterface;

    /**
     * Set the files which are to handle
     *
     * @param \phpDocumentor\Reflection\File\LocalFile[] $files
     * @return MultiDocRenderServiceInterface
     */
    public function files(array $files): MultiDocRenderServiceInterface;

    /**
     * Render the documentation from files
     *
     * @return MultiDocRenderServiceInterface
     */
    public function render(): MultiDocRenderServiceInterface;
}