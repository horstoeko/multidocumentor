<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for service class which will render the twig templates
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocTwigServiceInterface
{
    /**
     * Add a directory where to find the needed templates
     *
     * @param  string $directory
     * @return MultiDocTwigServiceInterface
     */
    public function addTemplateDirectory(string $directory): MultiDocTwigServiceInterface;

    /**
     * Render a twig remplate
     *
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render(string $name, array $data): string;
}