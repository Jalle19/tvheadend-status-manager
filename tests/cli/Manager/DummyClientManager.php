<?php

namespace Jalle19\StatusManager\Test\Manager;

use Jalle19\StatusManager\Manager\AbstractClientManager;

/**
 * Class DummyClientManager
 * @package   Jalle19\StatusManager\Test\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class DummyClientManager extends AbstractClientManager
{

	/**
	 * @return \SplObjectStorage
	 */
	public function getConnectedClients()
	{
		return $this->_connectedClients;
	}


	/**
	 * @return \SplObjectStorage
	 */
	public function getAuthenticatedClients()
	{
		return $this->_authenticatedClients;
	}

}
