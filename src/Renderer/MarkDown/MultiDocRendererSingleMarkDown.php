<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\MarkDown;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Renderer\MultiDocAbstractRenderer;
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
class MultiDocRendererSingleMarkDown extends MultiDocAbstractRenderer
{
    /**
     * The internal markup service
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface
     */
    protected $markupService;

    /**
     * Constructor
     */
    public function __construct(MultiDocConfig $config)
    {
        parent::__construct($config);

        $this->markupService = new MultiDocMarkupMarkdownService($this->config);
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return "singlemd";
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return "Renders a single markdown (.md) file";
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
