<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Events;

use horstoeko\multidocumentor\Events\MultiDocEvent;

/**
 * Class representing a MultiDoc Log Event
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocLogEvent extends MultiDocEvent
{
    /**
     * Log message
     *
     * @var string
     */
    protected $message = "";

    /**
     * Additional information for this event
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Constructor
     *
     * @param string $message
     * @param array  $parameters
     */
    public function __construct(string $message, ...$parameters)
    {
        $this->message = $message;
        $this->parameters = $parameters;
    }

    /**
     * Get the message content of the event
     *
     * @return string
     */
    public function getMessage(): string
    {
        return vsprintf($this->message, $this->parameters);
    }

    /**
     * Return additional information of th event
     *
     * @return array
     */
    public function getAdditionalInformation(): array
    {
        return $this->parameters;
    }
}
