<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Assets\MultiDocAssetManager;
use horstoeko\multidocumentor\Services\MultiDocMarkupService;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;

/**
 * service class which renders the output documents as an single PDF document
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
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
     */
    protected $htmlService;

    /**
     * Files to handle
     *
     * @param \phpDocumentor\Reflection\Php\File[] $file
     */
    protected $reflectedFiles = [];

    /**
     * Constructor
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;
        $this->htmlService = new MultiDocMarkupService($this->config);
    }

    /**
     * @inheritDoc
     */
    public function setReflectedFiles(array $files): MultiDocRendererInterface
    {
        $this->reflectedFiles = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
        $pdf = new MultiDocPdfFile();

        $pdf->WriteHTML(
            file_get_contents(MultiDocAssetManager::getHtmlDirectory() . DIRECTORY_SEPARATOR . 'styles.css'),
            \Mpdf\HTMLParserMode::HEADER_CSS
        );

        $this->htmlService->initializeService();

        foreach ($this->reflectedFiles as $file) {
            foreach ($file->getClasses() as $class) {
                $this->htmlService->createFromClass($class);
            }

            foreach ($file->getInterfaces() as $interface) {
                $this->htmlService->createFromInterface($interface);
            }

            foreach ($file->getTraits() as $trait) {
                $this->htmlService->createFromTrait($trait);
            }
        }

        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "doc.pdf";

        $pdf->WriteHTML((string)$this->htmlService);
        $pdf->Output($destinationFilename, 'F');

        return $this;
    }
}
