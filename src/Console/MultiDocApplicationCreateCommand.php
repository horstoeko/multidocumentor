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
use horstoeko\multidocumentor\Events\MultiDocLogEvent;
use horstoeko\multidocumentor\Services\MultiDocCreatorService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class representing the MultiDoc Console Application "Create"-Commands
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocApplicationCreateCommand extends MultiDocApplicationAbstractCommand implements EventSubscriberInterface
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
        $this->addOption('htmltemplates', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Additional html templates directories');
        $this->addOption('markdowntemplates', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Additional markdown templates directories');
        $this->addOption('css', null, InputOption::VALUE_REQUIRED, 'Directory where the css file is located');
        $this->addOption('options', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Additional options');
        $this->addOption('pageheader', null, InputOption::VALUE_REQUIRED, 'The content of the page header (PDF only)');
        $this->addOption('pagefooter', null, InputOption::VALUE_REQUIRED, 'The content of the page footer (PDF only)');
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
            "htmltemplates" => "arrayoption|array",
            "markdowntemplates" => "arrayoption|array",
            "css" => "stringoption:" . dirname(__FILE__) . "/../Assets/Html/styles.css|required",
            "options" => "arrayoption|array",
            "pageheader" => "stringoption:",
            "pagefooter" => "stringoption:{PAGENO}/{nbpg}",
        ];
    }

    /**
     * @inheritDoc
     */
    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $container = new MultiDocContainer();

        $container->getEventDispatcher()->addSubscriber($this);

        $container->setIncludeDirectories($this->validatedOption('include'));
        $container->setExcludeDirectories($this->validatedOption('exclude'));
        $container->setOutputTo($this->validatedOption('output'));
        $container->setOutputFormat($this->validatedOption('format'));
        $container->setFontDefault($this->validatedOption('fontdefault'));
        $container->setCustomRenderers($this->validatedOption('renderers'));
        $container->setCustomHtmlDirectories($this->validatedOption('htmltemplates'));
        $container->setCustomMarkdownDirectories($this->validatedOption('markdowntemplates'));
        $container->setCssFilename($this->validatedOption('css'));
        $container->setPageHeader($this->validatedOption('pageheader'));
        $container->setPageFooter($this->validatedOption('pagefooter'));

        foreach ($this->validatedOption("fontsettings") as $fontsetting) {
            list($fontName, $fontType, $fontFile) = explode(",", $fontsetting);
            $container->addFontsSettings($fontName, $fontType, $fontFile);
        }

        foreach ($this->validatedOption("options") as $option) {
            list($optionName, $optionValue) = explode(",", $option);
            $container->$optionName = $optionValue;
        }

        $creatorService = new MultiDocCreatorService($container);
        $creatorService->render();

        return MultiDocApplicationAbstractCommand::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MultiDocLogEvent::class => 'onLogEvent'
        ];
    }

    /**
     * Logging Event subscriber
     *
     * @param  MultiDocLogEvent $logEvent
     * @return void
     */
    public function onLogEvent(MultiDocLogEvent $logEvent): void
    {
        $this->outputInterface->writeln($logEvent->getMessage());
    }
}
