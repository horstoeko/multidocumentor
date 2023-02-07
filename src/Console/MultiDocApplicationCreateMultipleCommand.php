<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use horstoeko\multidocumentor\Config\MultiDocConfig;
use Symfony\Component\Console\Output\OutputInterface;
use horstoeko\multidocumentor\Services\MultiDocCreatorService;
use horstoeko\multidocumentor\Console\MultiDocApplicationAbstractCommand;

/**
 * Class representing the MultiDoc Console Application "CreateMultipleFormats"-Commands.
 * You can specify multiple output formats
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocApplicationCreateMultipleCommand extends MultiDocApplicationAbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('multidoc:createmultiple');
        $this->setDescription('Generate the documentation in multiple formats');
        $this->setHelp('Generate the documentation in multiple formats');
        $this->addOption('include', 'i', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Directory to include');
        $this->addOption('exclude', 'x', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Directory to exclude');
        $this->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Directory where the docs should be generated');
        $this->addOption('format', 'f', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The output format of documentation');
        $this->addOption('fontsettings', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Font settings');
        $this->addOption('fontdefault', null, InputOption::VALUE_REQUIRED, 'Set the default font');
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
            "format" => "arrayoption|array",
            "fontsettings" => "arrayoption|array",
            "fontdefault" => "stringoption:dejavusans",
        ];
    }

    /**
     * @inheritDoc
     */
    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->validatedOption('format') as $format) {
            if (($processResult = $this->handleFormat($input, $output, $format)) != MultiDocApplicationAbstractCommand::SUCCESS) {
                return $processResult;
            }
        }

        return MultiDocApplicationAbstractCommand::SUCCESS;
    }

    /**
     * Handle an output in a defined format
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @param  string          $format
     * @return integer
     */
    protected function handleFormat(InputInterface $input, OutputInterface $output, string $format): int
    {
        $command = $this->getApplication()->find('create');

        $arguments = [
            '--include' => $this->validatedOption('include'),
            '--exclude' => $this->validatedOption('exclude'),
            '--output' => $this->validatedOption('output'),
            '--format' => $format,
            '--fontdefault' => $this->validatedOption('fontdefault'),
            '--fontsettings' => $this->validatedOption('fontsettings'),
        ];

        $commandInput = new ArrayInput($arguments);

        return $command->run($commandInput, $output);
    }
}
