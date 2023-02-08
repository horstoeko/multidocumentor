<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Console\MultiDocApplicationAbstractCommand;
use horstoeko\multidocumentor\Services\MultiDocCreatorService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class representing the MultiDoc Console Application "Create"-Commands
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocApplicationCreateCommand extends MultiDocApplicationAbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('multidoc:create');
        $this->setDescription('Generate the documentation');
        $this->setHelp('Generate the documentation');
        $this->addOption('include', 'i', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Directory to include');
        $this->addOption('exclude', 'x', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Directory to exclude');
        $this->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Directory where the docs should be generated');
        $this->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'The output format of documentation');
        $this->addOption('fontsettings', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Font settings');
        $this->addOption('fontdefault', null, InputOption::VALUE_REQUIRED, 'Set the default font');
        $this->addOption('renderers', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Additional renderers');
        $this->addOption('options', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Additional options');
    }

    /**
     * @inheritDoc
     */
    protected function getValidationOptions(): array
    {
        return [
            "include" => "arrayoption|array",
            "exclude" => "arrayoption|array",
            "output" => "stringoption:./|required",
            "format" => "stringoption:singlemd|required",
            "fontsettings" => "arrayoption|array",
            "fontdefault" => "stringoption:dejavusans",
            "renderers" => "arrayoption|array",
            "options" => "arrayoption|array",
        ];
    }

    /**
     * @inheritDoc
     */
    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $config = new MultiDocConfig();

        $config->setIncludeDirectories($this->validatedOption('include'));
        $config->setExcludeDirectories($this->validatedOption('exclude'));
        $config->setOutputTo($this->validatedOption('output'));
        $config->setOutputFormat($this->validatedOption('format'));
        $config->setFontDefault($this->validatedOption('fontdefault'));
        $config->setCustomRenderers($this->validatedOption('renderers'));

        foreach ($this->validatedOption("fontsettings") as $fontsetting) {
            list($fontName, $fontType, $fontFile) = explode(",", $fontsetting);
            $config->addFontsSettings($fontName, $fontType, $fontFile);
        }

        foreach ($this->validatedOption("options") as $option) {
            list($optionName, $optionValue) = explode(",", $option);
            $config->$optionName = $optionValue;
        }

        $creatorService = new MultiDocCreatorService($config);
        $creatorService->render();

        return MultiDocApplicationAbstractCommand::SUCCESS;
    }
}
