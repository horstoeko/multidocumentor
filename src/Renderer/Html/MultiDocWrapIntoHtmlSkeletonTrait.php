<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Html;

/**
 * Trait for wrapping a rendererd HTML into a HTNL skeleton
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
trait MultiDocWrapIntoHtmlSkeletonTrait
{
    /**
     * Create inline fonts by font definition
     *
     * @return string
     */
    private function createInlineFonts(): string
    {
        $inlineFonts = "";

        foreach ($this->config->getFontsSettings() as $fontFamily => $fontsetting) {
            foreach ($fontsetting as $fontStyle => $fontFilename) {
                $fontfilename = $this->config->getFontsDirectory() . DIRECTORY_SEPARATOR . $fontFilename;

                if (!is_file($fontfilename)) {
                    continue;
                }

                $fontWeight = $fontStyle == "B" ? "bold" : "normal";
                $fontStyle = $fontStyle == "I" ? "italic" : "normal";

                $inlineFonts .= sprintf(
                    "@font-face {font-family: '%s'; font-style: %s; font-weight: %s; src: url(data:font/ttf;charset=utf-8;base64,%s)}\n",
                    $fontFamily,
                    $fontStyle,
                    $fontWeight,
                    \base64_encode(file_get_contents($fontfilename))
                );
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
        return file_get_contents($this->config->getHtmlDirectory() . DIRECTORY_SEPARATOR . 'styles.css');
    }

    /**
     * Create the HTML skeleton
     *
     * @return string
     */
    private function createHtmlSkeleton(): string
    {
        $skeleton = <<<SKELETON
        <!DOCTYPE html>
        <html>
          <head>
            <meta charset="utf-8">
            <title>Documentation</title>
            <style>
              {{inlinefonts}}
              {{inlinecss}}
            </style>
          </head>
          <body>
            {{content}}
          </body>
        </html>
        SKELETON;

        return $skeleton;
    }

    /**
     * Wrap rendered HTML into the skeleton
     *
     * @param string $renderedHtml
     * @return string
     */
    private function wrapIntoHtmlSkeleton(string $renderedHtml): string
    {
        return str_replace(
            ["{{inlinefonts}}", "{{inlinecss}}", "{{content}}"],
            [$this->createInlineFonts(), $this->createInlineCss(), $renderedHtml],
            $this->createHtmlSkeleton()
        );
    }
}
