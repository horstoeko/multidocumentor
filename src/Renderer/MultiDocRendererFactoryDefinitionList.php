<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer;

use horstoeko\multidocumentor\Tools\MultiDocTools;
use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererSinglePdf;
use horstoeko\multidocumentor\Renderer\Html\MultiDocRendererSingleHtml;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererMultiplePdf;
use horstoeko\multidocumentor\Renderer\Html\MultiDocRendererMultipleHtml;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererSingleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererMultipleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererSingleMarkDown as MultiDocRendererSingleMarkDownFromHtml;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererMultipleMarkDown as MultiDocRendererMultipleMarkDownFromHtml;

/**
 * class which is a list of factory definitions for a renderer
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererFactoryDefinitionList
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * A List of defined renderers
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface[]
     */
    protected $rendererInstances = [];

    /**
     * Constructor
     *
     * @param MultiDocConfig $config
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;

        $this->initDefaultRenderers();
        $this->initCustomRenderers();
    }

    /**
     * Find a renderer by it's registerd $index
     *
     * @param  integer $index
     * @param  boolean $raiseExceptionIfNotFound
     * @return MultiDocRendererInterface|null
     */
    public function findByIndex(int $index, bool $raiseExceptionIfNotFound = true): ?MultiDocRendererInterface
    {
        if (!isset($this->rendererInstances[$index])) {
            if ($raiseExceptionIfNotFound === true) {
                throw new \Exception(sprintf('No renderer registered at index %s', $index));
            }
            return null;
        }

        return $this->rendererInstances[$index];
    }

    /**
     * Find renderer by it's $name
     *
     * @param  string  $name
     * @param  boolean $raiseExceptionIfNotFound
     * @return MultiDocRendererInterface|null
     */
    public function findByName(string $name, bool $raiseExceptionIfNotFound = true): ?MultiDocRendererInterface
    {
        $rendererInstances = array_filter(
            $this->rendererInstances,
            function (MultiDocRendererInterface $instance) use ($name) {
                return strcasecmp($instance->getShortName(), $name) === 0;
            }
        );

        $rendererInstance = reset($rendererInstances);

        if ($rendererInstance === false) {
            if ($raiseExceptionIfNotFound === true) {
                throw new \Exception(sprintf('Cannot determine the renderer %s', $name));
            }
            return null;
        }

        return $rendererInstance;
    }

    /**
     * Check if a renderer has been registered on $index
     *
     * @param  int $index
     * @return boolean
     */
    public function existsByIndex(int $index): bool
    {
        return !is_null($this->findByIndex($index, false));
    }

    /**
     * Check if a renderer has been registered with $name
     *
     * @param  string $name
     * @return boolean
     */
    public function existsByName(string $name): bool
    {
        return !is_null($this->findByName($name, false));
    }

    /**
     * Returns a list of all registered renderers
     *
     * @return MultiDocRendererInterface[]
     */
    public function getAllRegisteredRenderers(): array
    {
        return $this->rendererInstances;
    }

    /**
     * Initialize a list of default renderers
     *
     * @return MultiDocRendererFactoryDefinitionList
     */
    private function initDefaultRenderers(): MultiDocRendererFactoryDefinitionList
    {
        $this->addRendererDefinition(
            MultiDocRendererSinglePdf::class
        )->addRendererDefinition(
            MultiDocRendererMultiplePdf::class
        )->addRendererDefinition(
            MultiDocRendererSingleMarkDownFromHtml::class
        )->addRendererDefinition(
            MultiDocRendererMultipleMarkDownFromHtml::class
        )->addRendererDefinition(
            MultiDocRendererSingleMarkDown::class
        )->addRendererDefinition(
            MultiDocRendererMultipleMarkDown::class
        )->addRendererDefinition(
            MultiDocRendererSingleHtml::class
        )->addRendererDefinition(
            MultiDocRendererMultipleHtml::class
        );

        return $this;
    }

    /**
     * Initialize custom renderers from config
     *
     * @return MultiDocRendererFactoryDefinitionList
     */
    private function initCustomRenderers(): MultiDocRendererFactoryDefinitionList
    {
        foreach ($this->config->getCustomRenderers() as $customRendererClassName) {
            if (MultiDocTools::classImplementsInterface($customRendererClassName, MultiDocRendererInterface::class) === true) {
                $this->addRendererDefinition($customRendererClassName);
            }
        }

        return $this;
    }

    /**
     * Add a renderer definition to list
     *
     * @param string $className
     * @return MultiDocRendererFactoryDefinitionList
     */
    private function addRendererDefinition(string $className): MultiDocRendererFactoryDefinitionList
    {
        $this->rendererInstances[] = new $className($this->config);
        return $this;
    }
}
