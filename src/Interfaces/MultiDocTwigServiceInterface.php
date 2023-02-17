<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

use horstoeko\multidocumentor\Container\MultiDocContainer;

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
     * Constructor
     */
    public function __construct(MultiDocContainer $container);

    /**
     * Add multiple directories where to find the needed templates
     *
     * @param  string[] $directories
     * @return MultiDocTwigServiceInterface
     */
    public function addTemplateDirectories(array $directories): MultiDocTwigServiceInterface;

    /**
     * Add a directory where to find the needed templates
     *
     * @param  string $directory
     * @return MultiDocTwigServiceInterface
     */
    public function addTemplateDirectory(string $directory): MultiDocTwigServiceInterface;

    /**
     * Returns true if template with $name exists, otherwise false
     *
     * @param  string $name
     * @return boolean
     */
    public function templateExists(string $name): bool;

    /**
     * Render a twig remplate defined by it's $name
     *
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function renderTemplate(string $name, array $data): string;
}
