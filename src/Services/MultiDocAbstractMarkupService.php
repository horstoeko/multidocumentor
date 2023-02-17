<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Services\MultiDocTwigService;
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
     * The HTML Engine
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocTwigServiceInterface
     */
    private $twigService;

    /**
     * The internal markup container
     *
     * @var string
     */
    protected $markup;

    /**
     * Constructur
     */
    public function __construct(MultiDocContainer $container)
    {
        $this->container = $container;
        $this->markup = "";

        $this->twigService = new MultiDocTwigService($this->container);
        $this->twigService->addTemplateDirectories($this->getCustomTemplateDirectories());
        $this->twigService->addTemplateDirectory($this->getDefaultTemplateDirectory());
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
    abstract public function getDefaultTemplateDirectory(): string;

    /**
     * @inheritDoc
     */
    abstract public function getCustomTemplateDirectories(): array;

    /**
     * @inheritDoc
     */
    public function beforeGetOutput(): MultiDocMarkupServiceInterface
    {
        return $this;
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
    public function render(string $name, array $data = array()): string
    {
        return $this->twigService->renderTemplate(
            $name,
            array_merge(
                $data,
                [
                    "_config" => $this->container,
                    "_container" => $this->container,
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function renderAndAddToOutput(string $name, array $data = array()): MultiDocMarkupServiceInterface
    {
        $this->addOutput($this->render($name, $data));

        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function writeIntroduction(): MultiDocMarkupServiceInterface;

    /**
     * @inheritDoc
     */
    abstract public function writeHeader(string $name, string $summary, string $description, array $tags): MultiDocMarkupServiceInterface;

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
