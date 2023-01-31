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
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererMultipleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererSingleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererMultipleMarkDown as MultiDocRendererMultipleMarkDownFromHtml;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererSingleMarkDown as MultiDocRendererSingleMarkDownFromHtml;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererMultiplePdf;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererSinglePdf;

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
            0 => MultiDocRendererSinglePdf::class,
            1 => MultiDocRendererMultiplePdf::class,
            2 => MultiDocRendererSingleMarkDownFromHtml::class,
            3 => MultiDocRendererMultipleMarkDownFromHtml::class,
            4 => MultiDocRendererSingleMarkDown::class,
            5 => MultiDocRendererMultipleMarkDown::class,
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
     * @param \horstoeko\multidocumentor\Config\MultiDocConfig $config
     * @return MultiDocRendererInterface
     */
    public static function createRenderer(MultiDocConfig $config): MultiDocRendererInterface
    {
        if (!self::hasRenderer($config->getOutputFormat())) {
            throw new \Exception('Cannot determine the renderer');
        }

        $classname = self::getAllRenderers()[$config->getOutputFormat()];

        return new $classname($config);
    }
}