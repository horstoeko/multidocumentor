<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\MarkDown;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Services\MultiDocMarkupMarkdownService;

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
     * The internal markup service
     *
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

        $this->markupService = new MultiDocMarkupMarkdownService($this->config);
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

        file_put_contents($destinationFilename, $this->markupService->getMarkupOutput());

        return $this;
    }
}
