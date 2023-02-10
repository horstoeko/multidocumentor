<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Services\MultiDocAbstractMarkupService;
use horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface;

/**
 * Service class which renders the markup in HTML format
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocMarkupHtmlService extends MultiDocAbstractMarkupService
{
    /**
     * @inheritDoc
     */
    public function getMarkupTemplateDirectory(): string
    {
        return $this->container->getHtmlDirectory();
    }

    /**
     * @inheritDoc
     */
    public function writeHeader(string $name, string $summary, string $description, array $tags): MultiDocMarkupServiceInterface
    {
        $this->renderAndAddToOutput(
            'header.twig',
            [
                'name' => $name,
                'summary' => $summary,
                'description' => $description,
                'tags' => $tags,
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
            'public' => [],
            'protected' => [],
            'private' => []
        );

        foreach ($constants as $constant) {
            $allConstants['public'][] = $constant->getName();
        }

        foreach ($properties as $property) {
            $allProperties[strval($property->getVisibility())][] = $property->getName();
        }

        foreach ($methods as $method) {
            $allMethods[strval($method->getVisibility())][] = $method->getName();
        }

        $this->renderAndAddToOutput(
            'summary.twig',
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
            $this->renderAndAddToOutput('constants.twig', array('constants' => $constants));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeProperties(array $properties): MultiDocMarkupServiceInterface
    {
        if (!empty($properties)) {
            $this->renderAndAddToOutput('properties.twig', array('properties' => $properties));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeMethods(array $methods): MultiDocMarkupServiceInterface
    {
        if (!empty($methods)) {
            $this->renderAndAddToOutput('methods.twig', array('methods' => $methods));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class): MultiDocMarkupServiceInterface
    {
        $summary = $class->getDocBlock() !== null ? $class->getDocBlock()->getSummary() : '';
        $description = $class->getDocBlock() !== null ? $class->getDocBlock()->getDescription() : '';
        $tags = $class->getDocBlock() !== null ? $class->getDocBlock()->getTags() : [];

        $this->writeHeader($class->getName(), $summary, $description, $tags);
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
        $tags = $interface->getDocBlock() !== null ? $interface->getDocBlock()->getTags() : [];

        $this->writeHeader($interface->getName(), $summary, $description, $tags);
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
        $tags = $trait->getDocBlock() !== null ? $trait->getDocBlock()->getTags() : [];

        $this->writeHeader($trait->getName(), $summary, $description, $tags);
        $this->writeSummary(array(), $trait->getProperties(), $trait->getMethods());
        $this->writeProperties($trait->getProperties());
        $this->writeMethods($trait->getMethods());

        return $this;
    }
}
