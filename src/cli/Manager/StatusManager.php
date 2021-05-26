<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Configuration\Instance;
use Jalle19\StatusManager\Event\ConnectionSeenEvent;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InputSeenEvent;
use Jalle19\StatusManager\Event\InstanceStatusCollectionRequestEvent;
use Jalle19\StatusManager\Event\InstanceSeenEvent;
use Jalle19\StatusManager\Event\InstanceStateEvent;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Event\SubscriptionSeenEvent;
use Jalle19\StatusManager\Event\SubscriptionStateChangeEvent;
use Jalle19\StatusManager\Instance\InstanceStatus;
use Jalle19\StatusManager\Instance\InstanceStatusCollection;
use Jalle19\StatusManager\Subscription\StateChangeParser;
use Jalle19\tvheadend\exception\RequestFailedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class StatusManager
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StatusManager extends AbstractManager implements EventSubscriberInterface
{

	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::MAIN_LOOP_STARTING => 'onMainLoopStarted',
			Events::MAIN_LOOP_TICK     => 'onMainLoopTick',
		];
	}


	/**
	 * Called right before the main loop is started
	 */
	public function onMainLoopStarted()
	{
		// Log information about the database
		$this->logger->notice('Using database at {databasePath}', [
			'databasePath' => $this->configuration->getDatabasePath(),
		]);

		// Log information about the configured instances
		$instances = $this->configuration->getInstances();

		$this->logger->notice('Managing {instances} instances:', [
			'instances' => count($instances),
		]);

		foreach ($instances as $instance)
		{
			$this->logger->notice('  {name} ({address}:{port})', [
				'name'    => $instance->getName(),
				'address' => $instance->getInstance()->getHostname(),
				'port'    => $instance->getInstance()->getPort(),
			]);

			$this->eventDispatcher->dispatch(Events::INSTANCE_SEEN, new InstanceSeenEvent($instance));
		}
	}


	/**
	 * Called periodically by the event loop
	 */
	public function onMainLoopTick()
	{
		/* @var InstanceStatusCollectionRequestEvent $event */
		$event = $this->eventDispatcher->dispatch(Events::INSTANCE_STATUS_COLLECTION_REQUEST,
			new InstanceStatusCollectionRequestEvent());

		$statusCollection = $this->getStatusMessages($event->getInstanceStatusCollection());

		foreach ($statusCollection->getInstanceStatuses() as $instanceStatus)
			$this->handleInstanceStatus($instanceStatus);

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATUS_UPDATES,
			new InstanceStatusUpdatesEvent($statusCollection));
	}


	/**
	 * @param InstanceStatus $instanceStatus
	 */
	private function handleInstanceStatus(InstanceStatus $instanceStatus)
	{
		$instanceName = $instanceStatus->getInstanceName();

		$this->logger->debug('Got status updates from {instanceName}', [
			'instanceName' => $instanceName,
		]);

		// Persist connections
		foreach ($instanceStatus->getConnections() as $connection)
		{
			$this->eventDispatcher->dispatch(Events::CONNECTION_SEEN,
				new ConnectionSeenEvent($instanceName, $connection));
		}

		// Persist inputs
		foreach ($instanceStatus->getInputs() as $input)
			$this->eventDispatcher->dispatch(Events::INPUT_SEEN,
				new InputSeenEvent($instanceName, $instanceStatus, $input));

		// Persist running subscriptions
		foreach ($instanceStatus->getSubscriptions() as $subscription)
		{
			$this->eventDispatcher->dispatch(Events::SUBSCRIPTION_SEEN,
				new SubscriptionSeenEvent($instanceName, $instanceStatus, $subscription));
		}

		// Handle subscription state changes
		foreach ($instanceStatus->getSubscriptionStateChanges() as $subscriptionStateChange)
		{
			$this->eventDispatcher->dispatch(Events::SUBSCRIPTION_STATE_CHANGE,
				new SubscriptionStateChangeEvent($instanceName, $subscriptionStateChange));
		}
	}


	/**
	 * Retrieves and returns all status messages for the configured
	 * instances
	 *
	 * @param \SplObjectStorage $instances the instances and their state
	 *
	 * @return InstanceStatusCollection
	 */
	private function getStatusMessages($instances)
	{
		$collection = new InstanceStatusCollection();

		foreach ($instances as $instance)
		{
			/* @var Instance $instance */
			$tvheadend     = $instance->getInstance();
			$instanceName  = $instance->getName();
			$instanceState = $instances[$instance];

			// Collect statuses from currently reachable instances
			if ($instanceState->isReachable())
			{
				try
				{
					$collection->add(new InstanceStatus(
						$instanceName,
						$tvheadend->getNetworks(),
						$tvheadend->getMultiplexes(),
						$tvheadend->getInputStatus(),
						$tvheadend->getSubscriptionStatus(),
						$tvheadend->getConnectionStatus(),
						StateChangeParser::parseStateChanges($tvheadend->getLogMessages())));

					$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_REACHABLE,
						new InstanceStateEvent($instance));
				}
				catch (\Exception $e)
				{
					if ($e instanceof RequestFailedException)
					{
						// Check for authentication errors
						$statusCode = $e->getResponse()->getStatusCode();

						if ($statusCode >= 400 && $statusCode < 500)
						{
							$this->logger->error('Authentication/authorization failed when connecting to {instanceName} (HTTP {statusCode})',
								[
									'instanceName' => $instanceName,
									'statusCode'   => $e->getResponse()->getStatusCode(),
								]);
						}
					}

					$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_UNREACHABLE,
						new InstanceStateEvent($instance));
				}
			}
			else
			{
				$this->eventDispatcher->dispatch(Events::INSTANCE_STATE_MAYBE_REACHABLE,
					new InstanceStateEvent($instance));
			}
		}

		return $collection;
	}

}
