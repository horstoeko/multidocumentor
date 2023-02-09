<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Interfaces\MultiDocTwigServiceInterface;
use horstoeko\multidocumentor\Twig\MultiDocTwigEngine;

/**
 * Service class which will render the twig templates
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocTwigService implements MultiDocTwigServiceInterface
{
    /**
     * Container (Settings)
     *
     * @var \horstoeko\multidocumentor\Container\MultiDocContainer
     */
    protected $container;

    /**
     * The Twig engine
     *
     * @var \horstoeko\multidocumentor\Twig\MultiDocTwigEngine
     */
    protected $twigEngine;

    /**
     * @inheritDoc
     */
    public function __construct(MultiDocContainer $container)
    {
        $this->container = $container;
        $this->twigEngine = new MultiDocTwigEngine();
    }

    /**
     * @inheritDoc
     */
    public function addTemplateDirectory(string $directory): MultiDocTwigServiceInterface
    {
        $this->twigEngine->addTemplateDirectory($directory);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $data): string
    {
        return $this->twigEngine->render($name, $data);
    }
}
