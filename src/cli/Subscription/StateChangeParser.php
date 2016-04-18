<?php

namespace Jalle19\StatusManager\Subscription;

use Jalle19\tvheadend\model\comet\LogMessageNotification;

/**
 * Helper for parsing subscription state changes from raw log messages
 *
 * @package Jalle19\StatusManager\Subscription
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StateChangeParser
{

	/**
	 * Parses the specified log message notifications into state change objects
	 *
	 * @param LogMessageNotification[] $logMessages
	 *
	 * @return StateChange[] eventual state changes parsed
	 */
	public static function parseStateChanges(array $logMessages)
	{
		$stateChanges = [];

		foreach ($logMessages as $logMessage)
		{
			$stateChange = self::parseMessage($logMessage->logtxt);

			if ($stateChange !== null)
				$stateChanges[] = $stateChange;
		}

		return $stateChanges;
	}


	/**
	 * Attempts to parse the specified log message into a state change object
	 *
	 * @param string $message
	 *
	 * @return StateChange|null
	 */
	private static function parseMessage($message)
	{
		$messageParts = explode(' ', $message);

		// Check if we're dealing with subscription notifications
		if (strpos($message, 'subscription') !== false)
		{
			$stateChange = new StateChange(self::getSubscriptionId($messageParts[3]));

			// Check the state
			if (strpos($message, 'subscribing on channel') !== false)
				$stateChange->setState(StateChange::STATE_SUBSCRIPTION_STARTED);
			elseif (strpos($message, 'unsubscribing from'))
				$stateChange->setState(StateChange::STATE_SUBSCRIPTION_STOPPED);

			return $stateChange;
		}

		return null;
	}


	/**
	 * Parses the specified partial log message into a decimal subscription ID
	 *
	 * @param string $messagePart
	 *
	 * @return int
	 */
	private static function getSubscriptionId($messagePart)
	{
		return hexdec(substr($messagePart, 0, strlen($messagePart) - 1));
	}

}
