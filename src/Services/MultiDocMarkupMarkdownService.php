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
 * Service class which renders the markup in markdown format
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocMarkupMarkdownService extends MultiDocAbstractMarkupService
{
    /**
     * @inheritDoc
     */
    public function getDefaultTemplateDirectory(): string
    {
        return $this->container->getMarkdownDirectory();
    }

    /**
     * @inheritDoc
     */
    public function getCustomTemplateDirectories(): array
    {
        return $this->container->getCustomMarkdownDirectories();
    }

    /**
     * @inheritDoc
     */
    public function writeIntroduction(): MultiDocMarkupServiceInterface
    {
        return $this->renderAndAddToOutput('introduction.twig', []);
    }

    /**
     * @inheritDoc
     */
    public function writeHeader(\phpDocumentor\Reflection\Element $object, string $name, string $summary, string $description, array $tags): MultiDocMarkupServiceInterface
    {
        return $this->renderAndAddToOutput(
            'header.twig',
            [
                'object' => $object,
                'objecttype' => get_class($object),
                'objectfqsen' => $object->getFqsen(),
                'objectname' => $name,
                'name' => $name,
                'summary' => $summary,
                'description' => $description,
                'tags' => $tags,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function writeSummary(\phpDocumentor\Reflection\Element $object, array $constants, array $properties, array $methods): MultiDocMarkupServiceInterface
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

        return $this->renderAndAddToOutput(
            'summary.twig',
            [
                'object' => $object,
                'objecttype' => get_class($object),
                'objectfqsen' => $object->getFqsen(),
                'objectname' => $object->getName(),
                'methods' => $allMethods,
                'properties' => $allProperties,
                'constants' => $allConstants
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function writeConstants(\phpDocumentor\Reflection\Element $object, array $constants): MultiDocMarkupServiceInterface
    {
        if (!empty($constants)) {
            $this->renderAndAddToOutput(
                'constants.twig',
                [
                    'object' => $object,
                    'objecttype' => get_class($object),
                    'objectfqsen' => $object->getFqsen(),
                    'objectname' => $object->getName(),
                    'constants' => $constants,
                ]
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeProperties(\phpDocumentor\Reflection\Element $object, array $properties): MultiDocMarkupServiceInterface
    {
        if (!empty($properties)) {
            $this->renderAndAddToOutput(
                'properties.twig',
                [
                    'object' => $object,
                    'objecttype' => get_class($object),
                    'objectfqsen' => $object->getFqsen(),
                    'objectname' => $object->getName(),
                    'properties' => $properties,
                ]
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeMethods(\phpDocumentor\Reflection\Element $object, array $methods): MultiDocMarkupServiceInterface
    {
        if (!empty($methods)) {
            $this->renderAndAddToOutput(
                'methods.twig',
                [
                    'object' => $object,
                    'objecttype' => get_class($object),
                    'objectfqsen' => $object->getFqsen(),
                    'objectname' => $object->getName(),
                    'methods' => $methods,
                ]
            );
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

        $this->writeHeader($class, $class->getName(), $summary, $description, $tags);
        $this->writeSummary($class, $class->getConstants(), $class->getProperties(), $class->getMethods());
        $this->writeConstants($class, $class->getConstants());
        $this->writeProperties($class, $class->getProperties());
        $this->writeMethods($class, $class->getMethods());

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

        $this->writeHeader($interface, $interface->getName(), $summary, $description, $tags);
        $this->writeSummary($interface, $interface->getConstants(), array(), $interface->getMethods());
        $this->writeConstants($interface, $interface->getConstants());
        $this->writeMethods($interface, $interface->getMethods());

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

        $this->writeHeader($trait, $trait->getName(), $summary, $description, $tags);
        $this->writeSummary($trait, array(), $trait->getProperties(), $trait->getMethods());
        $this->writeProperties($trait, $trait->getProperties());
        $this->writeMethods($trait, $trait->getMethods());

        return $this;
    }
}
