<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Twig;

use League\CommonMark\CommonMarkConverter;
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
            new TwigFilter("parsedownnop", [$this, 'parsedownNoParagrap'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Removes invisble Characters
     *
     * @param  string $string
     * @return string
     */
    public function removeInvisibleCharacters($string): string
    {
        return preg_replace('/[\x00-\x1F\x7F]/', '', $string);
    }

    /**
     * Parse markdown to HTML
     *
     * @param  string $markDownString
     * @return string
     */
    public function parsedown($markDownString): string
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $htmlString = $converter->convertToHtml($markDownString ?? "");

        return $htmlString;
    }

    /**
     * Parse markdown to HTML. The wrapping paragraph will be removed
     *
     * @param  string $markDownString
     * @return string
     */
    public function parsedownNoParagrap($markDownString): string
    {
        $htmlString = $this->parsedown($markDownString ?? "");
        $htmlString = preg_replace('!^<p>(.*?)</p>$!i', '$1', $htmlString);

        return $htmlString;
    }
}
