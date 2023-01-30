<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use horstoeko\multidocumentor\Config\MultiDocConfig;
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
     * Configuration
     *
     * @var \horstoeko\multidocumentor\Config\MultiDocConfig
     */
    protected $config;

    /**
     * Constructor
     *
     * @param MultiDocConfig $config
     */
    public function __construct(MultiDocConfig $config)
    {
        $this->config = $config;

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $defaultFontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $defaultFontData = $defaultFontConfig['fontdata'];

        parent::__construct(
            [
                'tempDir' => sys_get_temp_dir() . '/mpdf',
                'fontDir' => array_merge($defaultFontDirs, [$this->config->getFontsDirectory()]),
                'fontdata' => $defaultFontData + []
            ]
        );
    }
}
