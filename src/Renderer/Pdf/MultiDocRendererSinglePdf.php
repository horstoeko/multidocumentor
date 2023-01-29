<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use horstoeko\multidocumentor\Services\MultiDocHtmlService;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;

/**
 * class which renders the documentation to a single PDF
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererSinglePdf implements MultiDocRendererInterface
{
    /**
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocHtmlService
     */
    protected $htmlService;

    /**
     * Files to handle
     *
     * @param \phpDocumentor\Reflection\Php\File $file
     */
    protected $files = "";

    /**
     * Directory to which the docs should be published
     *
     * @var string
     */
    protected $outputTo = "";

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->htmlService = new MultiDocHtmlService();
    }

    /**
     * @inheritDoc
     */
    public function outputTo(string $outputTo): MultiDocRendererInterface
    {
        $this->outputTo = $outputTo;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function files(array $files): MultiDocRendererInterface
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
        $pdf = new MultiDocPdfFile();

        foreach ($this->files as $file) {
            $this->htmlService->init();

            foreach ($file->getClasses() as $class) {
                $this->htmlService->createFromClass($class);
            }

            foreach ($file->getInterfaces() as $interface) {
                $this->htmlService->createFromInterface($interface);
            }

            foreach ($file->getTraits() as $trait) {
                $this->htmlService->createFromTrait($trait);
            }

            $pdf->WriteHTML((string)$this->htmlService);
        }

        $destinationFilename = rtrim($this->outputTo, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "doc.pdf";

        $pdf->Output($destinationFilename, 'F');

        return $this;
    }
}
