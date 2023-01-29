<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

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
     * Finder Service
     *
     * @var \horstoeko\multidocumentor\Services\MultiDocFinderService
     */
    protected $finderService;

    /**
     * Render Service
     *
     * @var \horstoeko\multidocumentor\Services\MultiDocRenderService
     */
    protected $renderService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->finderService = new MultiDocFinderService();
        $this->renderService = new MultiDocRenderService();
    }

    /**
     * @inheritDoc
     */
    public function include($directory): MultiDocCreatorServiceInterface
    {
        $this->finderService->include($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exclude($directory): MultiDocCreatorServiceInterface
    {
        $this->finderService->exclude($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function outputTo(string $outputTo): MultiDocCreatorServiceInterface
    {
        $this->renderService->outputTo($outputTo);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function format(int $format): MultiDocCreatorServiceInterface
    {
        $this->renderService->format($format);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function process(): MultiDocCreatorServiceInterface
    {
        $this->renderService->files($this->finderService->filesAsPhpDocLocalFiles());
        $this->renderService->render();

        return $this;
    }
}