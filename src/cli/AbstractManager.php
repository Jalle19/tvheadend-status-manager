<?php

namespace Jalle19\StatusManager;

/**
 * Base class for all managers. Every manager can access the application, from which common things like the
 * configuration, the logger etc. can be accessed.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
abstract class AbstractManager
{

	/**
	 * @var Application
	 */
	private $_application;


	/**
	 * AbstractManager constructor.
	 *
	 * @param Application $application
	 */
	public function __construct(Application $application)
	{
		$this->_application = $application;
	}


	/**
	 * @return Application
	 */
	protected function getApplication()
	{
		return $this->_application;
	}

}
