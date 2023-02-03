<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Twig;

use Parsedown;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class for multiDoc twig extensions
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocTwigExtension extends AbstractExtension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter("removeinvisible", [$this, 'removeInvisibleCharacters'], ['is_safe' => ['html']]),
            new TwigFilter("parsedown", [$this, 'parsedown'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Removes invisble Characters
     *
     * @param string $string
     * @return void
     */
    public function removeInvisibleCharacters($string)
    {
        return preg_replace('/[\x00-\x1F\x7F]/', '', $string);
    }

    /**
     * Parse markdown to HTML
     *
     * @param string $string
     * @return void
     */
    public function parsedown($string)
    {
        $parseDown = new Parsedown();
        return $parseDown->text($string);
    }
}
