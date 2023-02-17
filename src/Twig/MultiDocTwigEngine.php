<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Twig;

use horstoeko\multidocumentor\Twig\MultiDocTwigExtension;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;

/**
 * Class which wraps base Twig Engine
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocTwigEngine
{
    /**
     * Twig template loader
     *
     * @var \Twig\Loader\FilesystemLoader
     */
    protected $twigLoader;

    /**
     * Twig Environment
     *
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->twigLoader = new TwigFilesystemLoader();
        $this->twigEnvironment = new TwigEnvironment($this->twigLoader);
        $this->twigEnvironment->addExtension(new MultiDocTwigExtension());
    }

    /**
     * Add a folder where templates are stored
     *
     * @param  string $directory
     * @return MultiDocTwigEngine
     */
    public function addTemplateDirectory(string $directory): MultiDocTwigEngine
    {
        $this->twigLoader->addPath($directory);
        return $this;
    }

    /**
     * Check if a template named with $name exists
     *
     * @param string $name
     * @return boolean
     */
    public function templateExists(string $name): bool
    {
        return $this->twigLoader->exists($name);
    }

    /**
     * Render a template
     *
     * @param  string $name
     * @param  array  $data
     * @return string
     */
    public function render(string $name, array $data): string
    {
        return $this->twigEnvironment->render($name, $data);
    }
}
