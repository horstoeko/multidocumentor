<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\MarkDownFromHtml;

use League\HTMLToMarkdown\HtmlConverter;
use horstoeko\multidocumentor\Services\MultiDocMarkupService;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;

/**
 * service class which renders the output documents as an single markdown document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererMultipleMarkDown implements MultiDocRendererInterface
{
    /**
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
     */
    protected $htmlService;

    /**
     * @var \League\HTMLToMarkdown\HtmlConverter
     */
    protected $htmlConverter;

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
        $this->htmlService = new MultiDocMarkupService();
        $this->htmlConverter = new HtmlConverter();
    }

    /**
     * @inheritDoc
     */
    public function setOutputTo(string $outputTo): MultiDocRendererInterface
    {
        $this->outputTo = $outputTo;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFiles(array $files): MultiDocRendererInterface
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
        foreach ($this->files as $file) {
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
     * @param string $destinationFilename
     * @return MultiDocRendererInterface
     */
    public function renderSingleMarkDown(string $destinationFilename): MultiDocRendererInterface
    {
        $markDown = $this->htmlConverter->convert((string)$this->htmlService);

        file_put_contents($destinationFilename, $markDown);

        return $this;
    }

    /**
     * Render a class markdown file
     *
     * @return MultiDocRendererInterface
     */
    public function renderClass($class): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->outputTo, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Class" . $class->getName() . ".md";

        $this->htmlService->initializeService();
        $this->htmlService->createFromClass($class);

        $this->renderSingleMarkDown($destinationFilename);

        return $this;
    }

    /**
     * Render a interface markdown file
     *
     * @return MultiDocRendererInterface
     */
    public function renderInterface($interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->outputTo, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Interface" . $interface->getName() . ".md";

        $this->htmlService->initializeService();
        $this->htmlService->createFromInterface($interface);

        $this->renderSingleMarkDown($destinationFilename);

        return $this;
    }

    /**
     * Render a interface markdown file
     *
     * @return MultiDocRendererInterface
     */
    public function renderTrait($interface): MultiDocRendererInterface
    {
        $destinationFilename = rtrim($this->outputTo, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "Traut" . $interface->getName() . ".md";

        $this->htmlService->initializeService();
        $this->htmlService->createFromTrait($interface);

        $this->renderSingleMarkDown($destinationFilename);

        return $this;
    }
}
