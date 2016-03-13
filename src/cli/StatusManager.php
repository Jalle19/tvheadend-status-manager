<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Configuration\Instance;
use Jalle19\StatusManager\Event\ConnectionSeenEvent;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InputSeenEvent;
use Jalle19\StatusManager\Event\InstanceSeenEvent;
use Jalle19\StatusManager\Event\InstanceStateEvent;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Event\SubscriptionSeenEvent;
use Jalle19\StatusManager\Event\SubscriptionStateChangeEvent;
use Jalle19\StatusManager\Instance\InstanceStatus;
use Jalle19\StatusManager\Instance\InstanceStatusCollection;
use Jalle19\StatusManager\Subscription\StateChangeParser;

/**
 * Class StatusManager
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatusManager extends AbstractManager
{

	/**
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
		$logger        = $this->getApplication()->getLogger();
		$configuration = $this->getApplication()->getConfiguration();

		// Log information about the database
		$logger->debug('Using database at {databasePath}', [
			'databasePath' => $configuration->getDatabasePath(),
		]);

		// Log information about the configured instances
		$instances = $configuration->getInstances();

		$logger->info('Managing {instances} instances:', [
			'instances' => count($instances),
		]);

		foreach ($instances as $configuredInstance)
		{
			$instance = $configuredInstance->getInstance();

			$logger->info('  {address}:{port}', [
				'address' => $instance->getHostname(),
				'port'    => $instance->getPort(),
			]);

			$this->getApplication()->getEventDispatcher()
			     ->dispatch(Events::INSTANCE_SEEN, new InstanceSeenEvent($instance));
		}
	}


	/**
	 * Handles the updates polled from the instances
	 */
	public function handleInstanceUpdates()
	{
		$logger          = $this->getApplication()->getLogger();
		$eventDispatcher = $this->getApplication()->getEventDispatcher();

		$statusCollection = $this->getStatusMessages();

		foreach ($statusCollection->getInstanceStatuses() as $instanceStatus)
		{
			$instanceName = $instanceStatus->getInstanceName();

			$logger->debug('Got status updates from {instanceName}', [
				'instanceName' => $instanceName,
			]);

			// Persist connections
			foreach ($instanceStatus->getConnections() as $connection)
			{
				$eventDispatcher->dispatch(Events::CONNECTION_SEEN,
					new ConnectionSeenEvent($instanceName, $connection));
			}

			// Persist inputs
			foreach ($instanceStatus->getInputs() as $input)
				$eventDispatcher->dispatch(Events::INPUT_SEEN, new InputSeenEvent($instanceName, $input));

			// Persist running subscriptions
			foreach ($instanceStatus->getSubscriptions() as $subscription)
			{
				$eventDispatcher->dispatch(Events::SUBSCRIPTION_SEEN,
					new SubscriptionSeenEvent($instanceName, $subscription));
			}

			// Handle subscription state changes
			foreach ($instanceStatus->getSubscriptionStateChanges() as $subscriptionStateChange)
			{
				$eventDispatcher->dispatch(Events::SUBSCRIPTION_STATE_CHANGE,
					new SubscriptionStateChangeEvent($instanceName, $subscriptionStateChange));
			}
		}

		$eventDispatcher->dispatch(Events::INSTANCE_STATUS_UPDATES,
			new InstanceStatusUpdatesEvent($statusCollection));
	}


	/**
	 * Retrieves and returns all status messages for the configured
	 * instances
	 * @return InstanceStatusCollection
	 */
	private function getStatusMessages()
	{
		$instanceStateManager = $this->getApplication()->getInstanceStateManager();
		$eventDispatcher      = $this->getApplication()->getEventDispatcher();

		$instances  = $instanceStateManager->getInstances();
		$collection = new InstanceStatusCollection();

		foreach ($instances as $instance)
		{
			/* @var Instance $instance */
			$tvheadend    = $instance->getInstance();
			$instanceName = $instance->getName();

			// Collect statuses from currently reachable instances
			if ($instanceStateManager->isReachable($instance))
			{
				try
				{
					$collection->add(new InstanceStatus(
						$instanceName,
						$tvheadend->getInputStatus(),
						$tvheadend->getSubscriptionStatus(),
						$tvheadend->getConnectionStatus(),
						StateChangeParser::parseStateChanges($tvheadend->getLogMessages())));

					$eventDispatcher->dispatch(Events::INSTANCE_STATE_REACHABLE,
						new InstanceStateEvent($instance));
				}
				catch (\Exception $e)
				{
					$eventDispatcher->dispatch(Events::INSTANCE_STATE_UNREACHABLE,
						new InstanceStateEvent($instance));
				}
			}
			else
			{
				$eventDispatcher->dispatch(Events::INSTANCE_STATE_MAYBE_REACHABLE,
					new InstanceStateEvent($instance));
			}
		}

		return $collection;
	}

}
