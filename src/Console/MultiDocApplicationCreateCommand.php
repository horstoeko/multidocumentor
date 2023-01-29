<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use horstoeko\multidocumentor\Services\MultiDocCreatorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
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
     * The creator service
     *
     * @var \horstoeko\multidocumentor\Services\MultiDocCreatorService
     */
    protected $creatorService;

    /**
     * @inheritDoc
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->creatorService = new MultiDocCreatorService();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('create');
        $this->setDescription('Generate the documentation');
        $this->setHelp('Generate the documentation');
        $this->addOption('include', 'i', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Directory to include');
        $this->addOption('exclude', 'x', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Directory to exclude');
        $this->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Directory where the docs should be generated');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->creatorService->include($input->getOption('include'));
        $this->creatorService->exclude($input->getOption('exclude'));
        $this->creatorService->outputTo($input->getOption('output'));
        $this->creatorService->process();

        return Command::SUCCESS;
    }
}
