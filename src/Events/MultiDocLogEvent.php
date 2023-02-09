<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Events;

use horstoeko\multidocumentor\Events\MultiDocEvent;
use horstoeko\multidocumentor\Resources\MultiDocMessageTexts;

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
     * An (optional) internal code for the message event
     *
     * @var string
     */
    protected $messageCode = "";

    /**
     * Additional information for this event
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Constructor
     *
     * @param string $messageCode
     * @param array $parameters
     */
    public function __construct(string $messageCode = "", array $parameters = [])
    {
        $this->messageCode = $messageCode;
        $this->parameters = $parameters;
    }

    /**
     * Get the message content of the event
     *
     * @return string
     */
    public function getMessage(): string
    {
        return MultiDocMessageTexts::getText($this->messageCode, $this->parameters);
    }

    /**
     * Get the internal (optional) message code
     *
     * @return string
     */
    public function getMessageCode(): string
    {
        return $this->messageCode;
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
