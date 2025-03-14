<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Resources;

/**
 * Class representing a container which holds all message texts (e.g. for logging/events)
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocMessageTexts
{
    /**
     * Available texts
     */
    public const TEXTS = [
        'STARTED_RENDERING' => "Rendering using renderer %s started...",
        'FINISHED_RENDERING' => "Rendering finished. Saved to %s.",
        'SAVING_RENDERED_OUTPUT' => "Saving rendered output to %s",
        'SAVED_RENDERED_OUTPUT' => "Saved rendered output",
        'STARTED_RENDER_CLASS' => "Rendering class %s",
        'STARTED_RENDER_INTERFACE' => "Rendering interface %s",
    ];

    /**
     * Get text by code
     *
     * @param  string $code
     * @param  array  $parameters
     * @return string
     */
    public static function getText(string $code, array $parameters = []): string
    {
        return vsprintf(self::TEXTS[$code] ?? $code, $parameters);
    }
}
