<?php

namespace Jalle19\StatusManager\Database;

use Jalle19\StatusManager\Database\Base\InputQuery as BaseInputQuery;
use Jalle19\StatusManager\Instance\InstanceStatus;
use Jalle19\tvheadend\model\SubscriptionStatus;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for performing query and update operations on the 'input' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class InputQuery extends BaseInputQuery
{

	/**
	 * @param string $uuid
	 *
	 * @return bool
	 */
	public function hasInput($uuid)
	{
		return $this->findPk($uuid) !== null;
	}


	/**
	 * @param string             $instanceName
	 * @param SubscriptionStatus $status
	 *
	 * @return $this|Criteria
	 */
	public function filterBySubscriptionStatus(
		$instanceName,
		InstanceStatus $instanceStatus,
		SubscriptionStatus $status
	) {

		return $this->filterByInstanceName($instanceName)
		            ->filterByNetwork(Subscription::parseNetwork($status, $instanceStatus->getAvailableNetworks()))
		            ->filterByMux(Subscription::parseMux($status, $instanceStatus->getAvailableMuxes()))
		            ->addDescendingOrderByColumn('started');
	}

}
