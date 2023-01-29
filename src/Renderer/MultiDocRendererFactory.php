<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer;

use horstoeko\multidocumentor\Interfaces\MultiDocRendererInterface;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererSinglePdf;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererMultiplePdf;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererSingleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererMultipleMarkDown;

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
     * Returns a list of all available renderers
     *
     * @return array
     */
    public static function getAllRenderers(): array
    {
        return [
            MultiDocRendererSinglePdf::class,
            MultiDocRendererMultiplePdf::class,
            MultiDocRendererSingleMarkDown::class,
            MultiDocRendererMultipleMarkDown::class,
        ];
    }

    /**
     * Returns true when a renderer for the given $format is available
     *
     * @param integer $format
     * @return boolean
     */
    public static function hasRenderer(int $format): bool
    {
        return isset(self::getAllRenderers()[$format]);
    }

    /**
     * Create a renderer by format identifiert
     *
     * @param integer $format
     * @return MultiDocRendererInterface
     */
    public static function createRenderer(int $format): MultiDocRendererInterface
    {
        if (!self::hasRenderer($format)) {
            throw new \Exception('Cannot determine the renderer');
        }

        $classname = self::getAllRenderers()[$format];

        return new $classname;
    }
}