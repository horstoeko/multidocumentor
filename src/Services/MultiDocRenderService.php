<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use phpDocumentor\Reflection\Php\ProjectFactory;
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
     * Files to handle
     *
     * @param \phpDocumentor\Reflection\File\LocalFile[] $files
     */
    protected $files = [];

    /**
     * Directory to which the docs should be published
     *
     * @var string
     */
    protected $outputTo = "";

    /**
     * The output format
     *
     * @var integer
     */
    protected $format = 0;

    /**
     * @inheritDoc
     */
    public function outputTo(string $outputTo): MultiDocRenderServiceInterface
    {
        $this->outputTo = $outputTo;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function format(int $format): MultiDocRenderServiceInterface
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function files(array $files): MultiDocRenderServiceInterface
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): MultiDocRenderServiceInterface
    {
        $projectFactory = ProjectFactory::createInstance();
        $project = $projectFactory->create('Project to document', $this->files);

        $renderer = MultiDocRendererFactory::createRenderer($this->format);
        $renderer->files($project->getFiles());
        $renderer->outputTo($this->outputTo);
        $renderer->render();

        return $this;
    }
}
