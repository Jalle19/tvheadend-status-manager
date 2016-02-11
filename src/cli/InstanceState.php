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

	const REACHABLE       = 0;
	const MAYBE_REACHABLE = 1;
	const UNREACHABLE     = 2;

	/**
	 * @var int the number of retries since marked as unreachable
	 */
	private $_retryCount = 0;

	/**
	 * @var int the instance reachability state
	 */
	private $_reachability = self::MAYBE_REACHABLE;


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
		return $this->_reachability === self::REACHABLE || $this->_reachability === self::MAYBE_REACHABLE;
	}


	/**
	 * @return int the instance reachability state
	 */
	public function getReachability()
	{
		return $this->_reachability;
	}


	/**
	 * Sets the reachability state of the instance
	 *
	 * @param int $reachable one of the reachability states
	 */
	public function setReachability($reachable)
	{
		$this->_reachability = $reachable;
	}

}
