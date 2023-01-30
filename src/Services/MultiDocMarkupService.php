<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Assets\MultiDocAssetManager;
use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface;
use League\Plates\Engine as PlatesEngine;

/**
 * Service class which renders the markup
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocMarkupService implements MultiDocMarkupServiceInterface
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * The HTML Engine
     *
     * @var \League\Plates\Engine
     */
    protected $templatesEngine;

    /**
     * The internal markup container
     *
     * @var string
     */
    protected $markup;

    /**
     * Constructur
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;

        $this->templatesEngine = new PlatesEngine(MultiDocAssetManager::getHtmlDirectory());
        $this->markup = "";
    }

    /**
     * @inheritDoc
     */
    public function initializeService(): MultiDocMarkupServiceInterface
    {
        $this->markup = "";
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMarkupOutput(): string
    {
        return $this->markup;
    }

    /**
     * @inheritDoc
     */
    public function writeHeader(string $name, string $summary, string $description): MultiDocMarkupServiceInterface
    {
        $this->markup .= $this->templatesEngine->render(
            'header',
            [
                'name' => $name,
                'summary' => $summary,
                'description' => $description,
            ]
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeSummary(array $constants, array $properties, array $methods): MultiDocMarkupServiceInterface
    {
        $allConstants = $allProperties = $allMethods = array(
            'public' => '',
            'protected' => '',
            'private' => ''
        );

        foreach ($constants as $constant) {
            $allConstants['public'] .= '<a href="#constant:' . $constant->getName() . '">' . $constant->getName() . '</a><br>';
        }

        foreach ($properties as $property) {
            $allProperties[strval($property->getVisibility())] .= '<a href="#property:' . $property->getName() . '">$' . $property->getName() . '</a><br>';
        }

        foreach ($methods as $method) {
            $allMethods[strval($method->getVisibility())] .= '<a href="#method:' . $method->getName() . '">' . $method->getName() . '</a><br>';
        }

        $this->markup .= $this->templatesEngine->render(
            'summary',
            [
                'methods' => $allMethods,
                'properties' => $allProperties,
                'constants' => $allConstants
            ]
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeConstants(array $constants): MultiDocMarkupServiceInterface
    {
        if (!empty($constants)) {
            $this->markup .= $this->templatesEngine->render('constants', array('constants' => $constants));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeProperties(array $properties): MultiDocMarkupServiceInterface
    {
        if (!empty($properties)) {
            $this->markup .= $this->templatesEngine->render('properties', array('properties' => $properties));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeMethods(array $methods): MultiDocMarkupServiceInterface
    {
        if (!empty($methods)) {
            $this->markup .= $this->templatesEngine->render('methods', array('methods' => $methods));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class): MultiDocMarkupServiceInterface
    {
        $parsedown = new \Parsedown();
        $summary = $class->getDocBlock() !== null ? $class->getDocBlock()->getSummary() : '';
        $description = $class->getDocBlock() !== null ? $parsedown->text($class->getDocBlock()->getDescription()) : '';

        $this->writeHeader($class->getName(), $summary, $description);
        $this->writeSummary($class->getConstants(), $class->getProperties(), $class->getMethods());
        $this->writeConstants($class->getConstants());
        $this->writeProperties($class->getProperties());
        $this->writeMethods($class->getMethods());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface): MultiDocMarkupServiceInterface
    {
        $summary = $interface->getDocBlock() !== null ? $interface->getDocBlock()->getSummary() : '';
        $description = $interface->getDocBlock() !== null ? $interface->getDocBlock()->getDescription() : '';

        $this->writeHeader($interface->getName(), $summary, $description);
        $this->writeSummary($interface->getConstants(), array(), $interface->getMethods());
        $this->writeConstants($interface->getConstants());
        $this->writeMethods($interface->getMethods());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait): MultiDocMarkupServiceInterface
    {
        $summary = $trait->getDocBlock() !== null ? $trait->getDocBlock()->getSummary() : '';
        $description = $trait->getDocBlock() !== null ? $trait->getDocBlock()->getDescription() : '';

        $this->writeHeader($trait->getName(), $summary, $description);
        $this->writeSummary(array(), $trait->getProperties(), $trait->getMethods());
        $this->writeProperties($trait->getProperties());
        $this->writeMethods($trait->getMethods());

        return $this;
    }
}
