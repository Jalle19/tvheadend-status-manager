<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\Subscription as BaseSubscription;
use Jalle19\tvheadend\model\multiplex\Multiplex;
use Jalle19\tvheadend\model\network\Network;
use Jalle19\tvheadend\model\SubscriptionStatus;

/**
 * @package   Jalle19\StatusManager\Database
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class Subscription extends BaseSubscription
{

	/**
	 * @param SubscriptionStatus $subscriptionStatus
	 * @param Multiplex[]        $availableMuxes
	 *
	 * @return string
	 */
	public static function parseMux(SubscriptionStatus $subscriptionStatus, array $availableMuxes)
	{
		$service = $subscriptionStatus->service;

		// Check if the service string contains /$mux/
		foreach ($availableMuxes as $mux)
		{
			if (strpos($service, '/' . $mux->name . '/') !== false)
			{
				return $mux->name;
			}
		}

		return null;
	}


	/**
	 * @param SubscriptionStatus $subscriptionStatus
	 * @param Network[]          $availableNetworks
	 *
	 * @return string
	 */
	public static function parseNetwork(SubscriptionStatus $subscriptionStatus, array $availableNetworks)
	{
		$service = $subscriptionStatus->service;

		// Check if the service string contains /$network/
		foreach ($availableNetworks as $network)
		{
			if (strpos($service, '/' . $network->networkname . '/') !== false)
			{
				return $network->networkname;
			}
		}

		return null;
	}
}
