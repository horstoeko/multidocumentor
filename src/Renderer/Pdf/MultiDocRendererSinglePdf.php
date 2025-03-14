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

/**
 * service class which renders the output documents as an single PDF document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererSinglePdf extends MultiDocAbstractRenderer
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
        return "singlepdf";
    }

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return "Renders a a single PDF (.pdf) file";
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
        $pdf = new MultiDocPdfFile($this->container);

        $pdf->WriteHTML(
            file_get_contents($this->container->getCssFilename()),
            \Mpdf\HTMLParserMode::HEADER_CSS
        );

        $this->markupService->initialize();
        $this->markupService->writeIntroduction();

        foreach ($this->reflectedFiles as $reflectedFile) {
            foreach ($reflectedFile->getClasses() as $class) {
                $this->markupService->createFromClass($class);
            }
            
            foreach ($reflectedFile->getInterfaces() as $interface) {
                $this->markupService->createFromInterface($interface);
            }
            
            foreach ($reflectedFile->getTraits() as $trait) {
                $this->markupService->createFromTrait($trait);
            }
        }

        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "doc.pdf";

        $pdf->WriteHTML($this->markupService->getOutput());
        $pdf->Output($destinationFilename, 'F');

        return $this;
    }
}
