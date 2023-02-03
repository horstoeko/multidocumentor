<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer;

/**
 * class which is a factory definition for a renderer
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererFactoryDefinition
{
    /**
     * A short name for a renderer
     *
     * @var string
     */
    protected $name = "";

    /**
     * A longer introduction for a renderer
     *
     * @var string
     */
    protected $description = "";

    /**
     * The classname of the renderer to use
     *
     * @var string
     */
    protected $classname = "";

    /**
     * Constructor
     *
     * @param string $name
     * @param string $description
     * @param string $classname
     */
    public function __construct(string $name, string $description, string $classname)
    {
        $this->name = $name;
        $this->description = $description;
        $this->classname = $classname;
    }

    /**
     * Create a new renderer definition
     *
     * @param  string $name
     * @param  string $description
     * @param  string $classname
     * @return MultiDocRendererFactoryDefinition
     */
    public static function make(string $name, string $description, string $classname): MultiDocRendererFactoryDefinition
    {
        return new self($name, $description, $classname);
    }

    /**
     * Returns the name of the renderer
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the description of the renderer
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the class name of the renderer
     *
     * @return string
     */
    public function getClassname(): string
    {
        return $this->classname;
    }
}
