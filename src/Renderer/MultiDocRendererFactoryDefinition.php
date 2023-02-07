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
use horstoeko\multidocumentor\Tools\MultiDocTools;

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
     * The classname of the renderer to use
     *
     * @var string
     */
    protected $classname = "";

    /**
     * The class instance
     *
     * @var \horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface
     */
    protected $classInstance = null;

    /**
     * Constructor
     *
     * @param MultiDocConfig $config
     * @param string $classname
     */
    public function __construct(MultiDocConfig $config, string $classname)
    {
        if (MultiDocTools::classImplementsInterface($classname, MultiDocRendererInterface::class) === false) {
            throw new \InvalidArgumentException(sprintf("%s does not implement %s", $classname, MultiDocRendererInterface::class));
        }

        $this->classname = $classname;
        $this->classInstance = new $classname($config);
    }

    /**
     * Create a new renderer definition
     *
     * @param MultiDocConfig $config
     * @param string $classname
     * @return MultiDocRendererFactoryDefinition
     */
    public static function make(MultiDocConfig $config, string $classname): MultiDocRendererFactoryDefinition
    {
        return new self($config, $classname);
    }

    /**
     * Returns the name of the renderer
     *
     * @return string
     */
    public function getShortName(): string
    {
        return $this->classname::getShortName();
    }

    /**
     * Returns the description of the renderer
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->classname::getDescription();
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

    /**
     * Instanciate the class
     *
     * @return MultiDocRendererInterface
     */
    public function getClassInstance(): MultiDocRendererInterface
    {
        return $this->classInstance;
    }
}
