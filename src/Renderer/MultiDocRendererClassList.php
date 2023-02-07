<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\Html\MultiDocRendererMultipleHtml;
use horstoeko\multidocumentor\Renderer\Html\MultiDocRendererSingleHtml;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererMultipleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererSingleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererMultipleMarkDown as MultiDocRendererMultipleMarkDownFromHtml;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererSingleMarkDown as MultiDocRendererSingleMarkDownFromHtml;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererMultiplePdf;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererSinglePdf;
use horstoeko\multidocumentor\Tools\MultiDocTools;

/**
 * Class which is a list of available renderer classes
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererClassList
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * A List of defined renderer classes
     *
     * @var string[]
     */
    protected $rendererClasses = [];

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
     * @return string|null
     */
    public function findByIndex(int $index, bool $raiseExceptionIfNotFound = true): ?string
    {
        if (!isset($this->rendererClasses[$index])) {
            if ($raiseExceptionIfNotFound === true) {
                throw new \Exception(sprintf('No renderer registered at index %s', $index));
            }
            return null;
        }

        return $this->rendererClasses[$index];
    }

    /**
     * Find renderer by it's $name
     *
     * @param  string  $name
     * @param  boolean $raiseExceptionIfNotFound
     * @return string|null
     */
    public function findByName(string $name, bool $raiseExceptionIfNotFound = true): ?string
    {
        $rendererClasses = array_filter(
            $this->rendererClasses,
            function ($class) use ($name) {
                return strcasecmp($class::getShortName(), $name) === 0;
            }
        );

        $rendererClass = reset($rendererClasses);

        if ($rendererClass === false) {
            if ($raiseExceptionIfNotFound === true) {
                throw new \Exception(sprintf('Cannot determine the renderer %s', $name));
            }
            return null;
        }

        return $rendererClass;
    }

    /**
     * Check if a renderer has been registered on $index
     *
     * @param  int $index
     * @return boolean
     */
    public function existsByIndex(int $index): bool
    {
        return $this->findByIndex($index, false) !== null;
    }

    /**
     * Check if a renderer has been registered with $name
     *
     * @param  string $name
     * @return boolean
     */
    public function existsByName(string $name): bool
    {
        return $this->findByName($name, false) !== null;
    }

    /**
     * Returns a list of all registered renderers
     *
     * @return string[]
     */
    public function getAllRegisteredRenderers(): array
    {
        return $this->rendererClasses;
    }

    /**
     * Initialize a list of default renderers
     *
     * @return MultiDocRendererClassList
     */
    private function initDefaultRenderers(): MultiDocRendererClassList
    {
        $this->addRendererClasses(
            [
                MultiDocRendererSinglePdf::class,
                MultiDocRendererMultiplePdf::class,
                MultiDocRendererSingleMarkDownFromHtml::class,
                MultiDocRendererMultipleMarkDownFromHtml::class,
                MultiDocRendererSingleMarkDown::class,
                MultiDocRendererMultipleMarkDown::class,
                MultiDocRendererSingleHtml::class,
                MultiDocRendererMultipleHtml::class,
            ]
        );

        return $this;
    }

    /**
     * Initialize custom renderers from config
     *
     * @return MultiDocRendererClassList
     */
    private function initCustomRenderers(): MultiDocRendererClassList
    {
        foreach ($this->config->getCustomRenderers() as $customRendererClassName) {
            $this->addRendererClass($customRendererClassName);
        }

        return $this;
    }

    /**
     * Add multiple renderer classes
     *
     * @param  string[] $classNames
     * @return MultiDocRendererClassList
     */
    private function addRendererClasses(array $classNames): MultiDocRendererClassList
    {
        foreach ($classNames as $className) {
            $this->addRendererClass($className);
        }

        return $this;
    }

    /**
     * Add a renderer class
     *
     * @param  string $className
     * @return MultiDocRendererClassList
     */
    private function addRendererClass(string $className): MultiDocRendererClassList
    {
        if (MultiDocTools::classImplementsInterface($className, MultiDocRendererInterface::class) !== true) {
            return $this;
        }

        $this->rendererClasses[] = $className;

        return $this;
    }
}
