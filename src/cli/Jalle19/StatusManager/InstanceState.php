<?php

namespace Jalle19\StatusManager;

/**
 * Represents the reachability of an instance.
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class InstanceState
{

	/**
	 * @var int the number of retries since marked as unreachable
	 */
	private $_retryCount = 0;

	/**
	 * @var bool whether the instance is reachable
	 */
	private $_reachable = true;


	/**
	 * Increments the retry counter
	 */
	public function incrementRetryCount()
	{
		$this->_retryCount++;
	}


	/**
	 * @return int the retry count
	 */
	public function getRetryCount()
	{
		return $this->_retryCount;
	}


	/**
	 * Resets the retry count
	 */
	public function resetRetryCount()
	{
		$this->_retryCount = 0;
	}


	/**
	 * @return bool whether the instance is reachable
	 */
	public function isReachable()
	{
		return $this->_reachable;
	}


	/**
	 * Sets the reachability state of the instance
	 *
	 * @param boolean $reachable
	 */
	public function setReachable($reachable)
	{
		$this->_reachable = $reachable;
	}

}
