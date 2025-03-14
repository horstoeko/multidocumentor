<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\MultiDocAbstractRenderer;
use horstoeko\multidocumentor\Services\MultiDocMarkupHtmlService;
use phpDocumentor\Reflection\Php\Class_ as PhpDocumentorClass;
use phpDocumentor\Reflection\Php\Interface_ as PhpDocumentorInterface;
use phpDocumentor\Reflection\Php\Trait_ as PhpDocumentorTrait;

/**
 * service class which renders the output documents as an single PDF document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererMultiplePdf extends MultiDocAbstractRenderer
{
    /**
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
     */
    protected $markupService;

    /**
     * Constructor
     */
    public function __construct(MultiDocContainer $container)
    {
        parent::__construct($container);

        $this->markupService = new MultiDocMarkupHtmlService($this->container);
    }

    /**
     * @inheritDoc
     */
    public static function getShortName(): string
    {
        return "multiplepdf";
    }

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return "Renders multiple PDF (.pdf) files";
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
        foreach ($this->reflectedFiles as $reflectedFile) {
            foreach ($reflectedFile->getClasses() as $class) {
                $this->renderClass($class);
            }
            
            foreach ($reflectedFile->getInterfaces() as $interface) {
                $this->renderInterface($interface);
            }
            
            foreach ($reflectedFile->getTraits() as $trait) {
                $this->renderTrait($trait);
            }
        }

        return $this;
    }

    /**
     * Render a single PDF file
     *
     * @param  string $destinationFilename
     * @return MultiDocRendererInterface
     */
    private function renderSingleHtml(string $destinationFilename): MultiDocRendererInterface
    {
        $pdf = new MultiDocPdfFile($this->container);
        $pdf->WriteHTML(
            file_get_contents($this->container->getCssFilename()),
            \Mpdf\HTMLParserMode::HEADER_CSS
        );
        $pdf->WriteHTML($this->markupService->getOutput());
        $pdf->Output($destinationFilename, 'F');

        return $this;
    }

    /**
     * Render a class pdf file
     *
     * @param  PhpDocumentorClass $class
     * @return MultiDocRendererInterface
     */
    private function renderClass(PhpDocumentorClass $class): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Class" . $class->getName() . ".pdf";

        $this->markupService->initialize();
        $this->markupService->createFromClass($class);

        $this->renderSingleHtml($destinationFilename);

        return $this;
    }

    /**
     * Render a interface pdf file
     *
     * @param  PhpDocumentorInterface $interface
     * @return MultiDocRendererInterface
     */
    private function renderInterface(PhpDocumentorInterface $interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Interface" . $interface->getName() . ".pdf";

        $this->markupService->initialize();
        $this->markupService->createFromInterface($interface);

        $this->renderSingleHtml($destinationFilename);

        return $this;
    }

    /**
     * Render a interface pdf file
     *
     * @param  PhpDocumentorTrait $interface
     * @return MultiDocRendererInterface
     */
    private function renderTrait(PhpDocumentorTrait $interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Trait" . $interface->getName() . ".pdf";

        $this->markupService->initialize();
        $this->markupService->createFromTrait($interface);

        $this->renderSingleHtml($destinationFilename);

        return $this;
    }
}
