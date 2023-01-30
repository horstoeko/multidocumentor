<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\MarkDownFromHtml;

use horstoeko\multidocumentor\Config\MultiDocConfig;
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
     * @var \League\HTMLToMarkdown\HtmlConverter
     */
    protected $htmlConverter;

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
        $this->htmlConverter = new HtmlConverter();
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
        $this->markupService->initializeService();

        foreach ($this->reflectedFiles as $file) {
            foreach ($file->getClasses() as $class) {
                $this->markupService->createFromClass($class);
            }

            foreach ($file->getInterfaces() as $interface) {
                $this->markupService->createFromInterface($interface);
            }

            foreach ($file->getTraits() as $trait) {
                $this->markupService->createFromTrait($trait);
            }
        }

        $destinationFilename = rtrim($this->config->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "doc.md";

        $markDown = $this->htmlConverter->convert((string)$this->markupService);

        file_put_contents($destinationFilename, $markDown);

        return $this;
    }
}
