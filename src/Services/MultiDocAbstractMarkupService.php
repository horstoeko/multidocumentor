<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface;

/**
 * Basic Service class which renders the markup
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
     * Container (Settings)
     *
     * @var \horstoeko\multidocumentor\Container\MultiDocContainer
     */
    protected $container;

    /**
     * The internal markup container
     *
     * @var string
     */
    protected $markup = "";

    /**
     * Constructur
     */
    public function __construct(MultiDocContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function initialize(): MultiDocMarkupServiceInterface
    {
        $this->markup = "";

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function beforeGetOutput(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getOutput(): string
    {
        $this->beforeGetOutput();

        return $this->markup;
    }

    /**
     * @inheritDoc
     */
    public function addOutput(string $stringToAddToMarkup): MultiDocMarkupServiceInterface
    {
        $this->markup .= $stringToAddToMarkup;

        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function render(string $name, array $data = []): string;

    /**
     * @inheritDoc
     */
    abstract public function renderAndAddToOutput(string $name, array $data = []): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeIntroduction(): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeHeader(\phpDocumentor\Reflection\Element $object, string $name, string $summary, string $description, array $tags): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeSummary(\phpDocumentor\Reflection\Element $object, array $constants, array $properties, array $methods): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeConstants(\phpDocumentor\Reflection\Element $object, array $constants): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeProperties(\phpDocumentor\Reflection\Element $object, array $properties): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeMethods(\phpDocumentor\Reflection\Element $object, array $methods): MultiDocMarkupServiceInterface;

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
