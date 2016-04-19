<?php

namespace Jalle19\StatusManager\Test\Message\Handler;

use Jalle19\StatusManager\Message\AbstractMessage;
use Jalle19\StatusManager\Message\Handler\HandlerInterface;
use Jalle19\StatusManager\Message\Response\UsersResponse;
use Ratchet\ConnectionInterface;

/**
 * Class DummyHandler
 * @package   Jalle19\StatusManager\Test\Message\Handler
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class DummyHandler implements HandlerInterface
{

	/**
	 * @inheritdoc
	 */
	public function handleMessage(AbstractMessage $message, ConnectionInterface $sender)
	{
		// Only handle one type of request
		if ($message->getType() === AbstractMessage::TYPE_USERS_REQUEST)
			return new UsersResponse([]);

		return false;
	}

}
