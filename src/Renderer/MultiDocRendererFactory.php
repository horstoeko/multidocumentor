<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer;

use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererSinglePdf;
use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;

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
     * @param integer $format
     * @return MultiDocRendererInterface
     */
    public static function createRenderer(int $format): MultiDocRendererInterface
    {
        $rendererClasses = [
            MultiDocRendererSinglePdf::class,
        ];

        if (!isset($rendererClasses[$format])) {
            throw new \Exception('Cannot determine the renderer');
        }

        return new $rendererClasses[$format]();
    }
}