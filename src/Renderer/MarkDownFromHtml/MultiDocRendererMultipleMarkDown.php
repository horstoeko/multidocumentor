<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\MarkDownFromHtml;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\MultiDocAbstractRenderer;
use horstoeko\multidocumentor\Services\MultiDocMarkupHtmlService;
use League\HTMLToMarkdown\HtmlConverter;
use phpDocumentor\Reflection\Php\Class_ as PhpDocumentorClass;
use phpDocumentor\Reflection\Php\Interface_ as PhpDocumentorInterface;
use phpDocumentor\Reflection\Php\Trait_ as PhpDocumentorTrait;

/**
 * service class which renders the output documents as an single markdown document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererMultipleMarkDown extends MultiDocAbstractRenderer
{
    /**
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
     */
    protected $markupService;

    /**
     * @var \League\HTMLToMarkdown\HtmlConverter
     */
    protected $htmlConverter;

    /**
     * Constructor
     */
    public function __construct(MultiDocConfig $config)
    {
        parent::__construct($config);

        $this->markupService = new MultiDocMarkupHtmlService($this->config);
        $this->htmlConverter = new HtmlConverter();
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return "multiplemdbyhtml";
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Renders multiple markdown (.md) files from a created HTML file";
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
     * Render a single markdown file
     *
     * @param  string $destinationFilename
     * @return MultiDocRendererInterface
     */
    private function renderSingleMarkDown(string $destinationFilename): MultiDocRendererInterface
    {
        $markDown = $this->htmlConverter->convert($this->markupService->getMarkupOutput());

        file_put_contents($destinationFilename, $markDown);

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
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Class" . $class->getName() . ".md";

        $this->markupService->initializeService();
        $this->markupService->createFromClass($class);

        $this->renderSingleMarkDown($destinationFilename);

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
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Interface" . $interface->getName() . ".md";

        $this->markupService->initializeService();
        $this->markupService->createFromInterface($interface);

        $this->renderSingleMarkDown($destinationFilename);

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
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Traut" . $interface->getName() . ".md";

        $this->markupService->initializeService();
        $this->markupService->createFromTrait($interface);

        $this->renderSingleMarkDown($destinationFilename);

        return $this;
    }
}
