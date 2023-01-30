<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\MarkDownFromHtml;

use horstoeko\multidocumentor\Services\MultiDocMarkupService;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use League\HTMLToMarkdown\HtmlConverter;

/**
 * service class which renders the output documents as an single markdown document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererSingleMarkDown implements MultiDocRendererInterface
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
        $this->htmlService->initializeService();

        foreach ($this->files as $file) {
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

        $destinationFilename = rtrim($this->outputTo, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "doc.md";

        $markDown = $this->htmlConverter->convert((string)$this->htmlService);

        file_put_contents($destinationFilename, $markDown);

        return $this;
    }
}
