<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use horstoeko\multidocumentor\Config\MultiDocConfig;
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
    public function __construct(MultiDocConfig $config)
    {
        parent::__construct($config);

        $this->markupService = new MultiDocMarkupHtmlService($this->config);
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
     * Render a single PDF file
     *
     * @param  string $destinationFilename
     * @return MultiDocRendererInterface
     */
    private function renderSingleMarkDown(string $destinationFilename): MultiDocRendererInterface
    {
        $pdf = new MultiDocPdfFile($this->config);
        $pdf->WriteHTML(
            file_get_contents($this->config->getHtmlDirectory() . DIRECTORY_SEPARATOR . 'styles.css'),
            \Mpdf\HTMLParserMode::HEADER_CSS
        );
        $pdf->WriteHTML($this->markupService->getMarkupOutput());
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
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Class" . $class->getName() . ".pdf";

        $this->markupService->initializeService();
        $this->markupService->createFromClass($class);

        $this->renderSingleMarkDown($destinationFilename);

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
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Interface" . $interface->getName() . ".pdf";

        $this->markupService->initializeService();
        $this->markupService->createFromInterface($interface);

        $this->renderSingleMarkDown($destinationFilename);

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
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Traut" . $interface->getName() . ".pdf";

        $this->markupService->initializeService();
        $this->markupService->createFromTrait($interface);

        $this->renderSingleMarkDown($destinationFilename);

        return $this;
    }
}
