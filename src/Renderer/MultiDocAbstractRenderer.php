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

/**
 * Basic class which renders the document(s)
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 *
 * @property string $shortName
 * @property string $description
 * @method string getShortName
 * @method string getDescription
 */
abstract class MultiDocAbstractRenderer implements MultiDocRendererInterface
{
    /**
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * Files to handle
     *
     * @param \phpDocumentor\Reflection\Php\File[] $file
     */
    protected $reflectedFiles = [];

    /**
     * Constructor
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Get the shortname for this renderer
     *
     * @return string
     */
    abstract public static function getShortName(): string;

    /**
     * Get a description for this renderer
     *
     * @return string
     */
    abstract public static function getDescription(): string;

    /**
     * @inheritDoc
     */
    public function setReflectedFiles(array $files): MultiDocRendererInterface
    {
        $this->reflectedFiles = $files;
        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function render(): MultiDocRendererInterface;

    /**
     * Magic method for gettings properties
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if (\strcasecmp($name, "shortName") === 0) {
            return get_class($this)::getShortName();
        }
        if (\strcasecmp($name, "description") === 0) {
            return get_class($this)::getDescription();
        }
    }

    /**
     * Magic methods for calling unknown method
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (\strcasecmp($name, "getShortName") === 0) {
            return get_class($this)::getShortName();
        }
        if (\strcasecmp($name, "getDescription") === 0) {
            return get_class($this)::getDescription();
        }
    }
}
