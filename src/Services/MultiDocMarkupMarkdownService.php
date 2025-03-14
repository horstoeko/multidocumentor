<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface;
use horstoeko\multidocumentor\Services\MultiDocAbstractMarkupService;

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
     * The HTML Engine
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocTwigServiceInterface
     */
    private $twigService;

    /**
     * Constructur
     */
    public function __construct(MultiDocContainer $container)
    {
        parent::__construct($container);

        $this->twigService = new MultiDocTwigService($this->container);
        $this->twigService->addTemplateDirectories($this->container->getCustomMarkdownDirectories());
        $this->twigService->addTemplateDirectory($this->container->getMarkdownDirectory());
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $data = []): string
    {
        return $this->twigService->renderTemplate(
            $name,
            array_merge(
                $data,
                [
                    "_config" => $this->container,
                    "_container" => $this->container,
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function renderAndAddToOutput(string $name, array $data = []): MultiDocMarkupServiceInterface
    {
        $this->addOutput($this->render($name, $data));

        return $this;
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
        $allConstants = [
            'public' => [],
            'protected' => [],
            'private' => []
        ];
        $allProperties = [
            'public' => [],
            'protected' => [],
            'private' => []
        ];
        $allMethods = [
            'public' => [],
            'protected' => [],
            'private' => []
        ];
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
        if ($constants !== []) {
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
        if ($properties !== []) {
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
        if ($methods !== []) {
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
        $summary = $class->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $class->getDocBlock()->getSummary() : '';
        $description = $class->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $class->getDocBlock()->getDescription() : '';
        $tags = $class->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $class->getDocBlock()->getTags() : [];

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
        $summary = $interface->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $interface->getDocBlock()->getSummary() : '';
        $description = $interface->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $interface->getDocBlock()->getDescription() : '';
        $tags = $interface->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $interface->getDocBlock()->getTags() : [];

        $this->writeHeader($interface, $interface->getName(), $summary, $description, $tags);
        $this->writeSummary($interface, $interface->getConstants(), [], $interface->getMethods());
        $this->writeConstants($interface, $interface->getConstants());
        $this->writeMethods($interface, $interface->getMethods());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createFromTrait(\phpDocumentor\Reflection\Php\Trait_ $trait): MultiDocMarkupServiceInterface
    {
        $summary = $trait->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $trait->getDocBlock()->getSummary() : '';
        $description = $trait->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $trait->getDocBlock()->getDescription() : '';
        $tags = $trait->getDocBlock() instanceof \phpDocumentor\Reflection\DocBlock ? $trait->getDocBlock()->getTags() : [];

        $this->writeHeader($trait, $trait->getName(), $summary, $description, $tags);
        $this->writeSummary($trait, [], $trait->getProperties(), $trait->getMethods());
        $this->writeProperties($trait, $trait->getProperties());
        $this->writeMethods($trait, $trait->getMethods());

        return $this;
    }
}
