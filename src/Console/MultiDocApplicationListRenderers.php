<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use horstoeko\multidocumentor\Console\MultiDocApplicationAbstractCommand;
use horstoeko\multidocumentor\Container\MultiDocContainer;
use horstoeko\multidocumentor\Renderer\MultiDocRendererClassList;
use Symfony\Component\Console\Helper\Table;
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
        $this->addOption('withclass', null, InputOption::VALUE_NONE, 'Show the classname of the renderer');
    }

    /**
     * @inheritDoc
     */
    protected function getValidationOptions(): array
    {
        return [
            "withclass" => "default:false|boolean",
        ];
    }

    /**
     * @inheritDoc
     */
    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $container = new MultiDocContainer();

        $renderersDefinitionList = new MultiDocRendererClassList($container);
        $renderers = $renderersDefinitionList->getAllRegisteredRenderers();

        $tableHeader = ['Index', 'Name', 'Description'];

        if ($this->validatedOption('withclass') === true) {
            $tableHeader[] = 'Class';
        }

        $table = new Table($output);
        $table->setHeaders($tableHeader);

        $tableItems = array_map(
            function ($renderer, $rendererKey) {
                $row = [$rendererKey, $renderer::getShortName(), $renderer::getDescription()];

                if ($this->validatedOption('withclass') === true) {
                    $row[] = $renderer;
                }

                return $row;
            },
            $renderers,
            array_keys($renderers)
        );

        $table->setRows($tableItems);
        $table->setStyle('box');
        $table->render();

        return MultiDocApplicationAbstractCommand::SUCCESS;
    }
}
