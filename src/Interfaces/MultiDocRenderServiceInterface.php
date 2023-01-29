<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for a service class which will render the documentation
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
    public function setOutputTo(string $outputTo): MultiDocRenderServiceInterface;

    /**
     * Set the output format
     *
     * @param integer $format
     * @return MultiDocRenderServiceInterface
     */
    public function setOutputFormat(int $format): MultiDocRenderServiceInterface;

    /**
     * Set the files which are to handle
     *
     * @param \phpDocumentor\Reflection\File\LocalFile[] $files
     * @return MultiDocRenderServiceInterface
     */
    public function setFiles(array $files): MultiDocRenderServiceInterface;

    /**
     * Render the documentation from files
     *
     * @return MultiDocRenderServiceInterface
     */
    public function renderDocumentation(): MultiDocRenderServiceInterface;
}