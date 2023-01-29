<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Renderer\Pdf;

use Mpdf\Mpdf;

/**
 * Class which implements a pdf file
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
     * Constructor
     *
     * @param string $path
     */
    public function __construct() {
        parent::__construct(['tempDir' => sys_get_temp_dir() . '/mpdf']);
    }

    /**
     * Generates PDF file.
     * @throws \Mpdf\MpdfException
     */
    private function save(): void {
        //$this->WriteHTML($this->html);
        //$this->output($this->path . '.pdf', 'F');
    }
}