<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocRenderServiceInterface;
use horstoeko\multidocumentor\Renderer\MultiDocRendererFactory;
use phpDocumentor\Reflection\Php\ProjectFactory;

/**
 * Service class which will render the documentation
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRenderService implements MultiDocRenderServiceInterface
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * Files to handle
     *
     * @param \phpDocumentor\Reflection\File\LocalFile[] $files
     */
    protected $localFiles = [];

    /**
     * Constructor
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function setIncludedFiles(array $files): MultiDocRenderServiceInterface
    {
        $this->localFiles = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRenderServiceInterface
    {
        $projectFactory = ProjectFactory::createInstance();

        /**
         * @var \phpDocumentor\Reflection\Php\Project
         */
        $project = $projectFactory->create('Project to document', $this->localFiles);

        $renderer = MultiDocRendererFactory::createRenderer($this->config);
        $renderer->setReflectedFiles($project->getFiles());
        $renderer->render();

        return $this;
    }
}
