<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Html;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\MultiDocAbstractRenderer;
use horstoeko\multidocumentor\Services\MultiDocMarkupHtmlSkeletonService;

/**
 * service class which renders the output documents as an single markdown document
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererSingleHtml extends MultiDocAbstractRenderer
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
    public function __construct(MultiDocContainer $container)
    {
        parent::__construct($container);

        $this->markupService = new MultiDocMarkupHtmlSkeletonService($this->container);
    }

    /**
     * @inheritDoc
     */
    public static function getShortName(): string
    {
        return "singlehtml";
    }

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return "Renders a single HTML files";
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRendererInterface
    {
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

        $destinationFilename = rtrim($this->container->getOutputTo(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "doc.html";

        file_put_contents($destinationFilename, $this->markupService->getOutput());

        return $this;
    }
}
