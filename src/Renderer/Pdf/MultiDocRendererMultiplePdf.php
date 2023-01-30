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
use horstoeko\multidocumentor\Services\MultiDocMarkupService;

/**
 * service class which renders the output documents as an single PDF document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererMultiplePdf implements MultiDocRendererInterface
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
    protected $markupService;

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
        $this->markupService = new MultiDocMarkupService($this->config);
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

    public function renderSingleMarkDown(string $destinationFilename): MultiDocRendererInterface
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
     * @return MultiDocRendererInterface
     */
    public function renderClass($class): MultiDocRendererInterface
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
     * @return MultiDocRendererInterface
     */
    public function renderInterface($interface): MultiDocRendererInterface
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
     * @return MultiDocRendererInterface
     */
    public function renderTrait($interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Traut" . $interface->getName() . ".pdf";

        $this->markupService->initializeService();
        $this->markupService->createFromTrait($interface);

        $this->renderSingleMarkDown($destinationFilename);

        return $this;
    }
}
