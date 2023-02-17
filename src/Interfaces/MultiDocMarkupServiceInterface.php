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
 * Interface for a service class which renders the markup
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocMarkupServiceInterface
{
    /**
     * Constructur
     */
    public function __construct(MultiDocContainer $container);

    /**
     * Initialize (e.g. the internal markup Content Container)
     *
     * @return MultiDocMarkupServiceInterface
     */
    public function initialize(): MultiDocMarkupServiceInterface;

    /**
     * Get the directory where all the markup template files are located
     *
     * @return string
     */
    public function getDefaultTemplateDirectory(): string;

    /**
     * Get the directory where all the user-defined markup template files are located
     *
     * @return array
     */
    public function getCustomTemplateDirectories(): array;

    /**
     * Trigger which called before the getMarkupOutput method is run
     *
     * @return MultiDocMarkupServiceInterface
     */
    public function beforeGetOutput(): MultiDocMarkupServiceInterface;

    /**
     * Return the created markup
     *
     * @return string
     */
    public function getOutput(): string;

    /**
     * Add data to markup output
     *
     * @param  string $stringToAddToMarkup
     * @return MultiDocMarkupServiceInterface
     */
    public function addOutput(string $stringToAddToMarkup): MultiDocMarkupServiceInterface;

    /**
     * Render a markup
     *
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render(string $name, array $data = array()): string;

    /**
     * Render a markup and add the rendered output to internal markup storage
     *
     * @param  string $name
     * @param  array  $data
     * @return MultiDocMarkupServiceInterface
     */
    public function renderAndAddToOutput(string $name, array $data = array()): MultiDocMarkupServiceInterface;

    /**
     * Write an introduction
     *
     * @return MultiDocMarkupServiceInterface
     */
    public function writeIntroduction(): MultiDocMarkupServiceInterface;

    /**
     * Write Header
     *
     * @param  string $name
     * @param  string $summary
     * @param  string $description
     * @param  array  $tags
     * @return MultiDocMarkupServiceInterface
     */
    public function writeHeader(string $name, string $summary, string $description, array $tags): MultiDocMarkupServiceInterface;

    /**
     * Write a summary
     *
     * @param  \phpDocumentor\Reflection\Php\Constant[] $constants
     * @param  \phpDocumentor\Reflection\Php\Property[] $properties
     * @param  \phpDocumentor\Reflection\Php\Method[]   $methods
     * @return MultiDocMarkupServiceInterface
     */
    public function writeSummary(array $constants, array $properties, array $methods): MultiDocMarkupServiceInterface;

    /**
     * Write constants
     *
     * @param  \phpDocumentor\Reflection\Php\Constant[] $constants
     * @return MultiDocMarkupServiceInterface
     */
    public function writeConstants(array $constants): MultiDocMarkupServiceInterface;

    /**
     * Write properties
     *
     * @param  \phpDocumentor\Reflection\Php\Property[] $properties
     * @return MultiDocMarkupServiceInterface
     */
    public function writeProperties(array $properties): MultiDocMarkupServiceInterface;

    /**
     * Write methods
     *
     * @param  \phpDocumentor\Reflection\Php\Method[] $methods
     * @return MultiDocMarkupServiceInterface
     */
    public function writeMethods(array $methods): MultiDocMarkupServiceInterface;

    /**
     * Generate class description
     *
     * @param  \phpDocumentor\Reflection\Php\Class_ $class
     * @return MultiDocMarkupServiceInterface
     */
    public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class): MultiDocMarkupServiceInterface;

    /**
     * Generate Interface description
     *
     * @param  \phpDocumentor\Reflection\Php\Interface_ $interface
     * @return MultiDocMarkupServiceInterface
     */
    public function createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface): MultiDocMarkupServiceInterface;

    /**
     * Generate Trait description
     *
     * @param  \phpDocumentor\Reflection\Php\Trait_ $trait
     * @return MultiDocMarkupServiceInterface
     */
    public function createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait): MultiDocMarkupServiceInterface;
}
