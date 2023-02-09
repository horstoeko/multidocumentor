<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Html;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\MultiDocAbstractRenderer;
use horstoeko\multidocumentor\Services\MultiDocMarkupHtmlSkeletonService;
use phpDocumentor\Reflection\Php\Class_ as PhpDocumentorClass;
use phpDocumentor\Reflection\Php\Interface_ as PhpDocumentorInterface;
use phpDocumentor\Reflection\Php\Trait_ as PhpDocumentorTrait;

/**
 * service class which renders the output as multiple HTML documents
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererMultipleHtml extends MultiDocAbstractRenderer
{
    /**
     * The internal markup service
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
     */
    protected $markupService;

    /**
     * Constructor
     */
    public function __construct(MultiDocContainer $container)
    {
        parent::__construct($container);

        $this->markupService = new MultiDocMarkupHtmlSkeletonService($this->container);
    }

    /**
     * @inheritDoc
     */
    public static function getShortName(): string
    {
        return "multiplehtml";
    }

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return "Renders multiple HTML files";
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
        foreach ($this->reflectedFiles as $file) {
            foreach ($file->getClasses() as $class) {
                $this->renderClass($class);
            }
            foreach ($file->getInterfaces() as $interface) {
                $this->renderInterface($interface);
            }
            foreach ($file->getTraits() as $trait) {
                $this->renderTrait($trait);
            }
        }

        return $this;
    }

    /**
     * Return CSS content
     *
     * @return string
     */
    public function getCss(): string
    {
        return file_get_contents($this->container->getHtmlDirectory() . DIRECTORY_SEPARATOR . 'styles.css');
    }

    /**
     * Render a single markdown file
     *
     * @param  string $destinationFilename
     * @return MultiDocRendererInterface
     */
    private function renderSingleHtml(string $destinationFilename): MultiDocRendererInterface
    {
        file_put_contents($destinationFilename, $this->markupService->getMarkupOutput());

        return $this;
    }

    /**
     * Render a class markdown file
     *
     * @param  PhpDocumentorClass $class
     * @return MultiDocRendererInterface
     */
    private function renderClass(PhpDocumentorClass $class): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Class" . $class->getName() . ".html";

        $this->markupService->initializeService();
        $this->markupService->createFromClass($class);

        $this->renderSingleHtml($destinationFilename);

        return $this;
    }

    /**
     * Render a interface markdown file
     *
     * @param  PhpDocumentorInterface $interface
     * @return MultiDocRendererInterface
     */
    private function renderInterface(PhpDocumentorInterface $interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Interface" . $interface->getName() . ".html";

        $this->markupService->initializeService();
        $this->markupService->createFromInterface($interface);

        $this->renderSingleHtml($destinationFilename);

        return $this;
    }

    /**
     * Render a interface markdown file
     *
     * @param  PhpDocumentorTrait $interface
     * @return MultiDocRendererInterface
     */
    private function renderTrait(PhpDocumentorTrait $interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Trait" . $interface->getName() . ".html";

        $this->markupService->initializeService();
        $this->markupService->createFromTrait($interface);

        $this->renderSingleHtml($destinationFilename);

        return $this;
    }
}
