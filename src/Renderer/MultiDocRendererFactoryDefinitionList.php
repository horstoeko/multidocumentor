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
     * @var \horstoeko\multidocumentor\Renderer\MultiDocRendererFactoryDefinition[]
     */
    protected $rendererDefinitions = [];

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
     * @return MultiDocRendererFactoryDefinition|null
     */
    public function findByIndex(int $index, bool $raiseExceptionIfNotFound = true): ?MultiDocRendererFactoryDefinition
    {
        if (!isset($this->rendererDefinitions[$index])) {
            if ($raiseExceptionIfNotFound === true) {
                throw new \Exception(sprintf('No renderer registered at index %s', $index));
            }
            return null;
        }

        return $this->rendererDefinitions[$index];
    }

    /**
     * Find renderer by it's $name
     *
     * @param  string  $name
     * @param  boolean $raiseExceptionIfNotFound
     * @return MultiDocRendererFactoryDefinition|null
     */
    public function findByName(string $name, bool $raiseExceptionIfNotFound = true): ?MultiDocRendererFactoryDefinition
    {
        $rendererDefinitions = array_filter(
            $this->rendererDefinitions,
            function (MultiDocRendererFactoryDefinition $definition) use ($name) {
                return strcasecmp($definition->getShortName(), $name) === 0;
            }
        );

        $rendererDefinitionsFirst = reset($rendererDefinitions);

        if ($rendererDefinitionsFirst === false) {
            if ($raiseExceptionIfNotFound === true) {
                throw new \Exception(sprintf('Cannot determine the renderer %s', $name));
            }
            return null;
        }

        return $rendererDefinitionsFirst;
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
     * @return \horstoeko\multidocumentor\Renderer\MultiDocRendererFactoryDefinition[]
     */
    public function getAllRegisteredRenderers(): array
    {
        return $this->rendererDefinitions;
    }

    /**
     * Initialize a list of default renderers
     *
     * @return MultiDocRendererFactoryDefinitionList
     */
    private function initDefaultRenderers(): MultiDocRendererFactoryDefinitionList
    {
        $this->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererSinglePdf::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererMultiplePdf::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererSingleMarkDownFromHtml::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererMultipleMarkDownFromHtml::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererSingleMarkDown::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererMultipleMarkDown::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererSingleHtml::class)
        )->addRendererDefinition(
            MultiDocRendererFactoryDefinition::make($this->config, MultiDocRendererMultipleHtml::class)
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
                $this->addRendererDefinition(MultiDocRendererFactoryDefinition::make($this->config, $customRendererClassName));
            }
        }

        return $this;
    }

    /**
     * Add a renderer definition to list
     *
     * @param  MultiDocRendererFactoryDefinition $rendererDefinition
     * @return MultiDocRendererFactoryDefinitionList
     */
    private function addRendererDefinition(MultiDocRendererFactoryDefinition $rendererDefinition): MultiDocRendererFactoryDefinitionList
    {
        $this->rendererDefinitions[] = $rendererDefinition;
        return $this;
    }
}
