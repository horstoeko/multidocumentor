<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use horstoeko\multidocumentor\Config\MultiDocConfig;
use horstoeko\multidocumentor\Config\MultiDocConfigJsonLoader;
use horstoeko\multidocumentor\Services\MultiDocCreatorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class representing the MultiDoc Console Application "Create"-Commands
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocApplicationCreateCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('create');
        $this->setDescription('Generate the documentation');
        $this->setHelp('Generate the documentation');
        $this->addOption('jsonconfig', 'j', InputOption::VALUE_REQUIRED, 'A JSON configuration file');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption("jsonconfig")) {
            $output->writeln("You must specify a JSON configuration file");
            return Command::FAILURE;
        }

        $configLoader = new MultiDocConfigJsonLoader();

        $config = $configLoader->loadFromJsonFile($input->getOption("jsonconfig"))->getConfig();

        $creatorService = new MultiDocCreatorService($config);
        $creatorService->render();

        return Command::SUCCESS;
    }
}
