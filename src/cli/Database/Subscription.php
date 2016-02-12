<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\Subscription as BaseSubscription;
use Jalle19\tvheadend\model\SubscriptionStatus;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Subscription extends BaseSubscription
{

	/**
	 * @param SubscriptionStatus $SubscriptionStatus
	 *
	 * @return string
	 */
	public static function parseMux(SubscriptionStatus $SubscriptionStatus)
	{
		$parts = self::getServiceParts($SubscriptionStatus);

		return $parts[1];
	}


	/**
	 * @param SubscriptionStatus $SubscriptionStatus
	 *
	 * @return string
	 */
	public static function parseNetwork(SubscriptionStatus $SubscriptionStatus)
	{
		$parts = self::getServiceParts($SubscriptionStatus);

		return $parts[0];
	}


	/**
	 * @param SubscriptionStatus $subscriptionStatus
	 *
	 * @return array
	 */
	private static function getServiceParts(SubscriptionStatus $subscriptionStatus)
	{
		$service = $subscriptionStatus->service;

		// Remove the channel name and the beginning slash from the service string
		$channel = $subscriptionStatus->channel;
		$service = preg_replace('/\/' . preg_quote($channel, '/') . '$/', '', $service);

		// Split on the first slash
		$slash = strpos($service, '/');

		return [
			substr($service, 0, $slash),
			substr($service, $slash + 1), // ignore the slash
		];
	}

}
