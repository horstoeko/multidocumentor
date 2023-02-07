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
use horstoeko\multidocumentor\Renderer\MultiDocRendererClassList;

/**
 * class which is a factory for a renderer
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocRendererFactory
{
    /**
     * Create a renderer by format identifiert
     *
     * @param  \horstoeko\multidocumentor\Config\MultiDocConfig $config
     * @return MultiDocRendererInterface
     */
    public static function createRenderer(MultiDocConfig $config): MultiDocRendererInterface
    {
        $rendererDefinitions = new MultiDocRendererClassList($config);
        $rendererClass = $rendererDefinitions->findByName($config->getOutputFormat());
        $rendererInstance = new $rendererClass($config);

        return $rendererInstance;
    }
}
