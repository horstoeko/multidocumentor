<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use horstoeko\multidocumentor\Console\MultiDocApplicationCreateCommand;
use horstoeko\multidocumentor\Console\MultiDocApplicationCreateMultipleCommand;
use horstoeko\multidocumentor\Console\MultiDocApplicationListRenderers;
use Symfony\Component\Console\Application as ConsoleApplication;
use Composer\InstalledVersions as ComposerInstalledVersions;

/**
 * Class representing the MultiDoc Console Application
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocApplication extends ConsoleApplication
{
    /**
     * Constructor
     *
     * @param string $name
     * @param string $version
     */
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        $name = $name == 'UNKNOWN' ? "MultiDoc Console Application" : $name;
        $version = $version == 'UNKNOWN' ? ComposerInstalledVersions::getVersion('horstoeko/multidocumentor') : $version;

        parent::__construct($name, $version);

        $this->add(new MultiDocApplicationCreateCommand());
        $this->add(new MultiDocApplicationCreateMultipleCommand());
        $this->add(new MultiDocApplicationListRenderers());
    }
}
