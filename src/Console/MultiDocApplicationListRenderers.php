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
use horstoeko\multidocumentor\Renderer\MultiDocRendererFactoryDefinitionList;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
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
class MultiDocApplicationListRenderers extends MultiDocApplicationAbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('multidoc:renderers');
        $this->setDescription('List all registed renderers');
        $this->setHelp('This shows a list all registed renderers');
    }

    /**
     * @inheritDoc
     */
    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $config = new MultiDocConfig();

        $renderersDefinitionList = new MultiDocRendererFactoryDefinitionList($config);
        $renderers = $renderersDefinitionList->getAllRegisteredRenderers();

        $table = new Table($output);
        $table->setHeaders(['Index', 'Name', 'Description']);

        $tableItems = array_map(
            function ($renderer) {
                return ['', $renderer->getShortName(), $renderer->getDescription()];
            },
            $renderers
        );

        $table->setRows($tableItems);
        $table->setStyle('box');
        $table->render();

        return MultiDocApplicationAbstractCommand::SUCCESS;
    }
}
