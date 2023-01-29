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
    public function __construct()
    {
        $this->finderService = new MultiDocFinderService();
        $this->renderService = new MultiDocRenderService();
    }

    /**
     * @inheritDoc
     */
    public function addDirectoryToInclude($directory): MultiDocCreatorServiceInterface
    {
        $this->finderService->addDirectoryToInclude($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addDirectoryToExclude($directory): MultiDocCreatorServiceInterface
    {
        $this->finderService->addDirectoryToExclude($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOutputTo(string $outputTo): MultiDocCreatorServiceInterface
    {
        $this->renderService->setOutputTo($outputTo);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOutputFormat(int $format): MultiDocCreatorServiceInterface
    {
        $this->renderService->setOutputFormat($format);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function process(): MultiDocCreatorServiceInterface
    {
        $this->renderService->setFiles($this->finderService->getAllFilesAsPhpDocLocalFiles());
        $this->renderService->renderDocumentation();

        return $this;
    }
}