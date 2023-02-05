<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Console\Validation;

use Rakit\Validation\Rule;
use Rakit\Validation\Rules\Interfaces\ModifyValue;

/**
 * Class representing the validation of a console array option
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocValidationArrayOption extends Rule implements ModifyValue
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "The :attribute default is an empty array";

    /**
     * Parameters
     *
     * @var array
     */
    protected $fillableParams = [];

    /**
     * Check the $value is an array
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function modifyValue($value)
    {
        return !is_array($value) ? [] : $value;
    }
}
