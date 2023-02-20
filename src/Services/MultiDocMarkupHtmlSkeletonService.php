<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Services;

use horstoeko\multidocumentor\Services\MultiDocMarkupHtmlService;
use horstoeko\multidocumentor\Interfaces\MultiDocMarkupServiceInterface;

/**
 * Service class which renders the markup in plain HTML format wrapped into
 * a HTML skeleton
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocMarkupHtmlSkeletonService extends MultiDocMarkupHtmlService
{
    /**
     * @inheritDoc
     *
     * @return MultiDocMarkupServiceInterface
     */
    public function beforeGetOutput(): MultiDocMarkupServiceInterface
    {
        $this->markup = $this->render(
            "skeleton.twig",
            [
                "inlinefonts" => $this->createInlineFonts(),
                "inlinecss" => $this->createInlineCss(),
                "content" => $this->markup,
            ]
        );

        return $this;
    }

    /**
     * Create inline fonts by font definition as an array evaluatable in twig template
     *
     * @return array
     */
    private function createInlineFonts(): array
    {
        $inlineFonts = [];

        foreach ($this->container->getFontsSettings() as $fontFamily => $fontsetting) {
            foreach ($fontsetting as $fontStyle => $fontFilename) {
                $fullQualifiedFontFilename = $this->container->getFontsDirectory() . DIRECTORY_SEPARATOR . $fontFilename;

                if (!is_file($fullQualifiedFontFilename)) {
                    continue;
                }

                $inlineFonts[] = [
                    "definedfontstyle" => $fontStyle,
                    "fontfilename" => $fontFilename,
                    "fontfamily" => $fontFamily,
                    "fontweight" => $fontStyle == "B" ? "bold" : "normal",
                    "fontstyle" => $fontStyle == "I" ? "italic" : "normal",
                    "fontbase64content" => \base64_encode(file_get_contents($fullQualifiedFontFilename)),
                ];
            }
        }

        return $inlineFonts;
    }

    /**
     * Create the inline CSS
     *
     * @return string
     */
    private function createInlineCss(): string
    {
        return file_get_contents($this->container->getCssFilename());
    }
}
