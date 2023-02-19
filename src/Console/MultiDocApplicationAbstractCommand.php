<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console;

use horstoeko\multidocumentor\Console\Validation\MultiDocValidationArrayOption;
use horstoeko\multidocumentor\Console\Validation\MultiDocValidationStringOption;
use Rakit\Validation\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class representing the MultiDoc base class for MultiDoc's console command
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
abstract class MultiDocApplicationAbstractCommand extends Command
{
    /**
     * The internal validation class
     *
     * @var \Rakit\Validation\Validator
     */
    protected $validator = null;

    /**
     * The internal validation class containing the validation resulst
     *
     * @var \Rakit\Validation\Validation
     */
    protected $validation = null;

    /**
     * The input interface
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $inputInterface = null;

    /**
     * The output interface
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $outputInterface = null;

    /**
     * @inheritDoc
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->validator = new Validator();
        $this->validator->addValidator('arrayoption', new MultiDocValidationArrayOption());
        $this->validator->addValidator('stringoption', new MultiDocValidationStringOption());
    }

    /**
     * Return a list of all validation options
     *
     * @return array
     */
    protected function getValidationOptions(): array
    {
        return [];
    }

    /**
     * Perform option validation
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return boolean
     */
    protected function validateOptions(InputInterface $input, OutputInterface $output): bool
    {
        $this->validation =
            $this->validator->make(
                $input->getArguments() + $input->getOptions(),
                $this->getValidationOptions()
            );

        $this->validation->validate();

        return $this->validation->passes();
    }

    /**
     * Perform option validation. If validation fails all errormessages will we be shown
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return boolean
     */
    protected function validateOptionsWithMessage(InputInterface $input, OutputInterface $output): bool
    {
        $validationSucceeded = $this->validateOptions($input, $output);

        if (!$validationSucceeded) {
            foreach ($this->validation->errors()->toArray() as $optionNane => $errorMessages) {
                $output->writeln(sprintf("Option <info>%s</info> is invalid:", $optionNane));
                foreach ($errorMessages as $errorMessage) {
                    $output->writeln(sprintf(" - %s", $errorMessage));
                }
            }
        }

        return $validationSucceeded;
    }

    /**
     * Return the validated value of an option with name $name
     *
     * @param  string $name The name of the option
     * @return mixed
     */
    protected function validatedOption(string $name)
    {
        $validData = $this->validation->getValidData();

        return $validData[$name];
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->inputInterface = $input;
        $this->outputInterface = $output;

        if ($this->validateOptionsWithMessage($input, $output) === false) {
            return Command::FAILURE;
        }

        return $this->handle($input, $output);
    }

    /**
     * Contains the business logic of the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return integer
     */
    abstract protected function handle(InputInterface $input, OutputInterface $output): int;
}
