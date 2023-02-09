<?php

/**
 * This file is a part of horstoeko/multidocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace horstoeko\multidocumentor\Events;

use horstoeko\multidocumentor\Events\MultiDocEvent;
use horstoeko\multidocumentor\Events\MultiDocLogEvent;
use horstoeko\multidocumentor\Resources\MultiDocMessageCodes;
use horstoeko\multidocumentor\Resources\MultiDocMessageTexts;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

/**
 * Class representing the MultiDoc Event Dispatcher
 *
 * @category MultiDocumentor
 * @package  MultiDocumentor
 * @author   D. Erling <horstoeko@erling.com.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://github.com/horstoeko/multidocumentor
 */
class MultiDocEventDispatcher extends SymfonyEventDispatcher
{
    /**
     * Dispatches a Log Event
     *
     * @param string $messageCode
     * @param array $parameters
     * @return MultiDocEvent
     */
    public function dispatchLogEvent(string $messageCode, array $parameters = []): MultiDocEvent
    {
        return $this->dispatch(new MultiDocLogEvent($messageCode, $parameters));
    }
}
