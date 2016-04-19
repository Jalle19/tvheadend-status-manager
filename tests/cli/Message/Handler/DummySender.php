<?php

namespace Jalle19\StatusManager\Test\Message\Handler;

use Ratchet\ConnectionInterface;

/**
 * Class DummySender
 * @package   Jalle19\StatusManager\Test\Message\Handler
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class DummySender implements ConnectionInterface
{

	/**
	 * @inheritdoc
	 */
	function send($data)
	{

	}


	/**
	 * @inheritdoc
	 */
	function close()
	{

	}

}
