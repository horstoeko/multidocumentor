<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface;
use League\Plates\Engine as PlatesEngine;

/**
 * Service class which renders the markup
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
abstract class MultiDocAbstractMarkupService implements MultiDocMarkupServiceInterface
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * The HTML Engine
     *
     * @var \League\Plates\Engine
     */
    protected $templatesEngine;

    /**
     * The internal markup container
     *
     * @var string
     */
    protected $markup;

    /**
     * Constructur
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;
        $this->markup = "";

        $this->templatesEngine = new PlatesEngine($this->config->getHtmlDirectory());
    }

    /**
     * @inheritDoc
     */
    public function initializeService(): MultiDocMarkupServiceInterface
    {
        $this->markup = "";
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMarkupOutput(): string
    {
        return $this->markup;
    }

    /**
     * @inheritDoc
     */
    abstract public function getMarkupDirectory(): string;

    /**
     * @inheritDoc
     */
    abstract public function writeHeader(string $name, string $summary, string $description): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeSummary(array $constants, array $properties, array $methods): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeConstants(array $constants): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeProperties(array $properties): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeMethods(array $methods): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait): MultiDocMarkupServiceInterface;
}
