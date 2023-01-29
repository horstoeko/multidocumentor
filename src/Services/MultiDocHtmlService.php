<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Assets\MultiDocAssetManager;
use League\Plates\Engine as PlatesEngine;
use horstoeko\multidocumentor\Interfaces\MultiDocHtmlServiceInterface;

/**
 * Service class which renders the html
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocHtmlService implements MultiDocHtmlServiceInterface
{
    /**
     * Undocumented variable
     *
     * @var Engine
     */
    protected $templatesEngine;

    /**
     * The internal HTML container
     *
     * @var string
     */
    protected $html;

    /**
     * Constructur
     */
    public function __construct()
    {
        $this->templatesEngine = new PlatesEngine(MultiDocAssetManager::getHtmlDirectory());
        $this->html = "";
    }

    /**
     * @inheritDoc
     */
    public function initializeService(): MultiDocHtmlServiceInterface
    {
        $this->html = "";
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHtmlOutput(): string
    {
        return $this->html;
    }

    /**
     * @inheritDoc
     */
    public function writeHeader(string $name, string $summary, string $description): MultiDocHtmlServiceInterface
    {
        $this->html .= $this->templatesEngine->render(
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
    public function writeSummary(array $constants, array $properties, array $methods): MultiDocHtmlServiceInterface
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

        $this->html .= $this->templatesEngine->render(
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
    public function writeConstants(array $constants): MultiDocHtmlServiceInterface
    {
        if (!empty($constants)) {
            $this->html .= $this->templatesEngine->render('constants', array('constants' => $constants));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeProperties(array $properties): MultiDocHtmlServiceInterface
    {
        if (!empty($properties)) {
            $this->html .= $this->templatesEngine->render('properties', array('properties' => $properties));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeMethods(array $methods): MultiDocHtmlServiceInterface
    {
        if (!empty($methods)) {
            $this->html .= $this->templatesEngine->render('methods', array('methods' => $methods));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class): MultiDocHtmlServiceInterface
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
    public function createFromInterface(\phpDocumentor\Reflection\Php\Interface_ $interface): MultiDocHtmlServiceInterface
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
    public function createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait): MultiDocHtmlServiceInterface
    {
        $summary = $trait->getDocBlock() !== null ? $trait->getDocBlock()->getSummary() : '';
        $description = $trait->getDocBlock() !== null ? $trait->getDocBlock()->getDescription() : '';

        $this->writeHeader($trait->getName(), $summary, $description);
        $this->writeSummary(array(), $trait->getProperties(), $trait->getMethods());
        $this->writeProperties($trait->getProperties());
        $this->writeMethods($trait->getMethods());

        return $this;
    }

    /**
     * Magic mathod - returns the result as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->html;
    }
}
