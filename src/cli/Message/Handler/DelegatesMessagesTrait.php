<?php

namespace Jalle19\StatusManager\Message\Handler;

use Jalle19\StatusManager\Exception\UnhandledMessageException;
use Jalle19\StatusManager\Message\AbstractMessage;

/**
 * Class DelegatesMessagesTrait
 * @package   Jalle19\StatusManager\Message\Handler
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
trait DelegatesMessagesTrait
{

	/**
	 * @var HandlerInterface[]
	 */
	private $_handlers;


	/**
	 * @param HandlerInterface $handler
	 */
	public function registerMessageHandler(HandlerInterface $handler)
	{
		$this->_handlers[] = $handler;
	}


	/**
	 * @param AbstractMessage $message
	 *
	 * @return AbstractMessage
	 *
	 * @throws UnhandledMessageException
	 */
	public function tryDelegateMessage(AbstractMessage $message)
	{
		foreach ($this->_handlers as $handler)
		{
			$response = $handler->handleMessage($message);

			if ($response !== false)
				return $response;
		}

		throw new UnhandledMessageException();
	}

}
