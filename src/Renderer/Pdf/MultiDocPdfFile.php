<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use horstoeko\multidocumentor\Container\MultiDocContainer;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

/**
 * Class which creates a pdf file
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocPdfFile extends Mpdf
{
    /**
     * Container (Settings)
     *
     * @var \horstoeko\multidocumentor\Container\MultiDocContainer
     */
    protected $container;

    /**
     * Constructor
     *
     * @param MultiDocContainer $container
     */
    public function __construct(MultiDocContainer $container)
    {
        $this->container = $container;

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $defaultFontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $defaultFontData = $defaultFontConfig['fontdata'];

        $this->SetHeader($this->container->pageHeader);
        $this->SetFooter($this->container->pageFooter);

        parent::__construct(
            [
                'tempDir' => sys_get_temp_dir() . '/mpdf',
                'fontDir' => array_merge($defaultFontDirs, [$this->container->getFontsDirectory()]),
                'fontdata' => $defaultFontData + $this->container->getFontsSettings(),
                'default_font' => $this->container->getFontDefault(),
            ]
        );
    }
}
