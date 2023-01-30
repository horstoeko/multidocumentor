<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Services\MultiDocFinderService;
use horstoeko\multidocumentor\Interfaces\MultiDocCreatorServiceInterface;

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
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

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
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;

        $this->finderService = new MultiDocFinderService($config);
        $this->renderService = new MultiDocRenderService($config);
    }

    /**
     * @inheritDoc
     */
    public function renderDocumentation(): MultiDocCreatorServiceInterface
    {
        $this->renderService->setLocalFiles($this->finderService->getAllFilesAsPhpDocLocalFiles());
        $this->renderService->renderDocumentation();

        return $this;
    }
}