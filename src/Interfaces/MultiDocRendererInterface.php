<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for a service class which renders the output documents
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocRendererInterface
{
    /**
     * Set the file to render
     *
     * @param \phpDocumentor\Reflection\Php\File[] $files
     * @return MultiDocRendererInterface
     */
    public function setReflectedFiles(array $files): MultiDocRendererInterface;

    /**
     * Render the file
     *
     * @return MultiDocRendererInterface
     */
    public function render(): MultiDocRendererInterface;
}