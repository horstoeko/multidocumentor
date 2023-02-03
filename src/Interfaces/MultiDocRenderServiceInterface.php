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
     * Set the files which are to handle
     *
     * @param  \phpDocumentor\Reflection\File\LocalFile[] $files
     * @return MultiDocRenderServiceInterface
     */
    public function setLocalFiles(array $files): MultiDocRenderServiceInterface;

    /**
     * Render the documentation from files
     *
     * @return MultiDocRenderServiceInterface
     */
    public function render(): MultiDocRenderServiceInterface;
}