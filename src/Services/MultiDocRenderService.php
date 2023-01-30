<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use phpDocumentor\Reflection\Php\ProjectFactory;
use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Renderer\MultiDocRendererFactory;
use horstoeko\multidocumentor\Interfaces\MultiDocRenderServiceInterface;

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
    protected $files = [];

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
    public function setFiles(array $files): MultiDocRenderServiceInterface
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function renderDocumentation(): MultiDocRenderServiceInterface
    {
        $projectFactory = ProjectFactory::createInstance();
        $project = $projectFactory->create('Project to document', $this->files);

        $renderer = MultiDocRendererFactory::createRenderer($this->config);
        $renderer->setFiles($project->getFiles());
        $renderer->render();

        return $this;
    }
}
