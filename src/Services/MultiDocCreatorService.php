<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocCreatorServiceInterface;
use horstoeko\multidocumentor\Services\MultiDocFinderService;

/**
 * Service class which will create the documentation
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocCreatorService implements MultiDocCreatorServiceInterface
{
    /**
     * Container (Settings)
     *
     * @var \horstoeko\multidocumentor\Container\MultiDocContainer
     */
    protected $container;

    /**
     * Finder Service
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocFinderServiceInterface
     */
    protected $finderService;

    /**
     * Render Service
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocRenderServiceInterface
     */
    protected $renderService;

    /**
     * Constructor
     */
    public function __construct(MultiDocContainer $container)
    {
        ini_set("pcre.backtrack_limit", "5000000");

        $this->container = $container;

        $this->finderService = new MultiDocFinderService($container);
        $this->renderService = new MultiDocRenderService($container);
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocCreatorServiceInterface
    {
        $files = $this->finderService->getAllFilesAsPhpDocLocalFiles();

        $this->renderService->setIncludedFiles($files);
        $this->renderService->render();

        return $this;
    }
}
