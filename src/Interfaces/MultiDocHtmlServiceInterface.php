<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Interfaces;

/**
 * Interface for a class which renders the html
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
interface MultiDocHtmlServiceInterface
{
    /**
     * Initialize (e.g. the internal HTML Content Container)
     *
     * @return MultiDocHtmlServiceInterface
     */
    public function initializeService(): MultiDocHtmlServiceInterface;

    /**
     * Return the created HTML
     *
     * @return string
     */
    public function getHtmlOutput(): string;

    /**
     * Write Header
     *
     * @param string $name
     * @param string $summary
     * @param string $description
     * @return MultiDocHtmlServiceInterface
     */
    public function writeHeader(string $name, string $summary, string $description): MultiDocHtmlServiceInterface;

    /**
     * Write a summary
     *
     * @param \phpDocumentor\Reflection\Php\Constant[] $constants
     * @param \phpDocumentor\Reflection\Php\Property[] $properties
     * @param \phpDocumentor\Reflection\Php\Method[] $methods
     * @return MultiDocHtmlServiceInterface
     */
    public function writeSummary(array $constants, array $properties, array $methods): MultiDocHtmlServiceInterface;

    /**
     * Write constants
     *
     * @param \phpDocumentor\Reflection\Php\Constant[] $constants
     * @return MultiDocHtmlServiceInterface
     */
    public function writeConstants(array $constants): MultiDocHtmlServiceInterface;

    /**
     * Write properties
     *
     * @param \phpDocumentor\Reflection\Php\Property[] $properties
     * @return MultiDocHtmlServiceInterface
     */
    public function writeProperties(array $properties): MultiDocHtmlServiceInterface;

    /**
     * Write methods
     *
     * @param \phpDocumentor\Reflection\Php\Method[] $methods
     * @return MultiDocHtmlServiceInterface
     */
    public function writeMethods(array $methods): MultiDocHtmlServiceInterface;

    /**
     * Generate class description
     *
     * @param \phpDocumentor\Reflection\Php\Class_ $class
     * @return MultiDocHtmlServiceInterface
     */
    public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class): MultiDocHtmlServiceInterface;

    /**
     * Generate Interface description
     *
     * @param \phpDocumentor\Reflection\Php\Interface_ $interface
     * @return MultiDocHtmlServiceInterface
     */
    public function createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface): MultiDocHtmlServiceInterface;

    /**
     * Generate Trait description
     *
     * @param \phpDocumentor\Reflection\Php\Trait_ $trait
     * @return MultiDocHtmlServiceInterface
     */
    public function createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait): MultiDocHtmlServiceInterface;
}
