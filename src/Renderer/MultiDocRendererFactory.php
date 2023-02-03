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
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererSinglePdf;
use horstoeko\multidocumentor\Renderer\Pdf\MultiDocRendererMultiplePdf;
use horstoeko\multidocumentor\Renderer\MultiDocRendererFactoryDefinitionList;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererSingleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDown\MultiDocRendererMultipleMarkDown;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererSingleMarkDown as MultiDocRendererSingleMarkDownFromHtml;
use horstoeko\multidocumentor\Renderer\MarkDownFromHtml\MultiDocRendererMultipleMarkDown as MultiDocRendererMultipleMarkDownFromHtml;

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
     * @param \horstoeko\multidocumentor\Config\MultiDocConfig $config
     * @return MultiDocRendererInterface
     */
    public static function createRenderer(MultiDocConfig $config): MultiDocRendererInterface
    {
        $rendererDefinitions = new MultiDocRendererFactoryDefinitionList($config);
        $rendererDefinition = $rendererDefinitions->findByName($config->getOutputFormat());

        $classname = $rendererDefinition->getClassname();

        return new $classname($config);
    }
}