<?php

namespace Jalle19\StatusManager\Message\Handler;

use Jalle19\StatusManager\Exception\RequestFailedException;
use Jalle19\StatusManager\Message\AbstractMessage;
use Ratchet\ConnectionInterface;

/**
 * Interface HandlerInterface
 * @package   Jalle19\StatusManager\Message\Handler
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
interface HandlerInterface
{

	/**
	 * @param AbstractMessage     $message
	 * @param ConnectionInterface $sender
	 *
	 * @return AbstractMessage|false
	 * @throws RequestFailedException if the request fails
	 */
	public function handleMessage(AbstractMessage $message, ConnectionInterface $sender);

}
