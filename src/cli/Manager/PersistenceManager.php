<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\StatusManager\Database;
use Jalle19\StatusManager\Database\Channel;
use Jalle19\StatusManager\Database\ChannelQuery;
use Jalle19\StatusManager\Database\Connection;
use Jalle19\StatusManager\Database\ConnectionQuery;
use Jalle19\StatusManager\Database\Input;
use Jalle19\StatusManager\Database\InputError;
use Jalle19\StatusManager\Database\InputQuery;
use Jalle19\StatusManager\Database\InstanceQuery;
use Jalle19\StatusManager\Database\Subscription;
use Jalle19\StatusManager\Database\SubscriptionQuery;
use Jalle19\StatusManager\Database\User;
use Jalle19\StatusManager\Database\UserQuery;
use Jalle19\StatusManager\Event\ConnectionSeenEvent;
use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InputSeenEvent;
use Jalle19\StatusManager\Event\InstanceSeenEvent;
use Jalle19\StatusManager\Event\PersistInputErrorEvent;
use Jalle19\StatusManager\Event\SubscriptionSeenEvent;
use Jalle19\StatusManager\Event\SubscriptionStateChangeEvent;
use Jalle19\StatusManager\Subscription\StateChange;
use Jalle19\tvheadend\model\SubscriptionStatus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles persisting of objects to the database
 *
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class PersistenceManager extends AbstractManager implements EventSubscriberInterface
{

	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::MAIN_LOOP_STARTING        => 'onMainLoopStarted',
			Events::INSTANCE_SEEN             => 'onInstanceSeen',
			Events::CONNECTION_SEEN           => 'onConnectionSeen',
			Events::INPUT_SEEN                => 'onInputSeen',
			Events::SUBSCRIPTION_SEEN         => 'onSubscriptionSeen',
			Events::SUBSCRIPTION_STATE_CHANGE => 'onSubscriptionStateChange',
			Events::PERSIST_INPUT_ERROR       => 'onInputError',
		];
	}


	/**
	 * Removes stale subscriptions that haven't received a stop event
	 */
	public function onMainLoopStarted()
	{
		$numRemoved = SubscriptionQuery::create()->filterByStopped(null)->delete();

		$this->logger->info('Removed {numRemoved} stale subscriptions', [
			'numRemoved' => $numRemoved,
		]);
	}


	/**
	 * @param InstanceSeenEvent $event
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function onInstanceSeen(InstanceSeenEvent $event)
	{
		$instance = $event->getInstance();

		if (InstanceQuery::create()->hasInstance($instance))
			return;

		$instanceModel = new Database\Instance();
		$instanceModel->setPrimaryKey($instance->getHostname());
		$instanceModel->save();

		$this->logger->info('Stored new instance {instanceName}', [
			'instanceName' => $instance->getHostname(),
		]);

		// Create a special user for eventual DVR subscriptions
		$user = new User();
		$user->setInstance($instanceModel);
		$user->setName(User::NAME_DVR);
		$user->save();

		$this->logger
			->info('Stored new special user (instance: {instanceName}, user: {userName})', [
				'instanceName' => $instance->getHostname(),
				'userName'     => $user->getName(),
			]);
	}


	/**
	 * @param ConnectionSeenEvent $event
	 */
	public function onConnectionSeen(ConnectionSeenEvent $event)
	{
		$instanceName     = $event->getInstance();
		$connectionStatus = $event->getConnection();

		if (ConnectionQuery::create()->hasConnection($instanceName, $connectionStatus))
			return;

		$user = null;

		// Find the user object when applicable
		if (!$connectionStatus->isAnonymous())
		{
			$this->onUserSeen($instanceName, $connectionStatus->user);

			$user = UserQuery::create()->filterByInstanceName($instanceName)->filterByName($connectionStatus->user)
			                 ->findOne();
		}

		$connection = new Connection();
		$connection->setInstanceName($instanceName)->setPeer($connectionStatus->peer)
		           ->setUser($user)
		           ->setStarted($connectionStatus->started)->setType($connectionStatus->type)->save();

		$this->logger->info('Stored new connection (instance: {instanceName}, peer: {peer})', [
			'instanceName' => $instanceName,
			'peer'         => $connectionStatus->peer,
		]);
	}


	/**
	 * @param InputSeenEvent $event
	 */
	public function onInputSeen(InputSeenEvent $event)
	{
		$instanceName = $event->getInstance();
		$inputStatus  = $event->getInputStatus();

		// Update the input and started fields for existing inputs
		if (InputQuery::create()->hasInput($inputStatus->uuid))
		{
			$input = InputQuery::create()->findPk($inputStatus->uuid);
			$input->setStarted(new \DateTime())->setWeight($inputStatus->weight);

			return;
		}

		$input = new Input();
		$input->setInstanceName($instanceName)
		      ->setStarted(new \DateTime())
		      ->setFromInputStatus($inputStatus)->save();

		$this->logger
			->info('Stored new input (instance: {instanceName}, network: {network}, mux: {mux}, weight: {weight})',
				[
					'instanceName' => $instanceName,
					'network'      => $input->getNetwork(),
					'mux'          => $input->getMux(),
					'weight'       => $input->getWeight(),
				]);
	}


	/**
	 * @param SubscriptionSeenEvent $event
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function onSubscriptionSeen(SubscriptionSeenEvent $event)
	{
		$instanceName = $event->getInstance();
		$status       = $event->getSubscription();

		// Ignore certain subscriptions
		if (in_array($status->getType(), [SubscriptionStatus::TYPE_EPGGRAB, SubscriptionStatus::TYPE_SERVICE_OR_MUX]))
			return;

		// Determine the username to store for the subscription
		$username = $status->username;

		switch ($status->getType())
		{
			case SubscriptionStatus::TYPE_RECORDING:
				$username = User::NAME_DVR;
				break;
		}

		// Get the instance, user and channel
		$instance = InstanceQuery::create()->findPk($instanceName);
		$user     = UserQuery::create()->filterByInstance($instance)->filterByName($username)->findOne();

		// Ensure the channel exists
		$this->onChannelSeen($instanceName, $status->channel);
		$channel = ChannelQuery::create()->filterByInstance($instance)->filterByName($status->channel)->findOne();

		if (SubscriptionQuery::create()->hasSubscription($instance, $user, $channel, $status))
			return;

		// Try to determine which input is used by the subscription
		$input = InputQuery::create()->filterBySubscriptionStatus($instanceName, $status)->findOne();

		if ($input === null)
		{
			$this->logger
				->warning('Got subscription that cannot be tied to an input ({instanceName}, user: {userName}, channel: {channelName})',
					[
						'instanceName' => $instanceName,
						'userName'     => $user !== null ? $user->getName() : 'N/A',
						'channelName'  => $channel->getName(),
					]);
		}

		$subscription = new Subscription();
		$subscription->setInstance($instance)->setInput($input)->setUser($user)->setChannel($channel)
		             ->setSubscriptionId($status->id)->setStarted($status->start)->setTitle($status->title)
		             ->setService($status->service);
		$subscription->save();

		$this->logger
			->info('Stored new subscription (instance: {instanceName}, user: {userName}, channel: {channelName})',
				[
					'instanceName' => $instanceName,
					'userName'     => $user !== null ? $user->getName() : 'N/A',
					'channelName'  => $channel->getName(),
				]);
	}


	/**
	 * @param SubscriptionStateChangeEvent $event
	 */
	public function onSubscriptionStateChange(SubscriptionStateChangeEvent $event)
	{
		$instanceName = $event->getInstance();
		$stateChange  = $event->getStateChange();

		// We only need to persist subscription stops
		if ($stateChange->getState() === StateChange::STATE_SUBSCRIPTION_STARTED)
			return;

		// Find the latest matching subscription
		$subscription = SubscriptionQuery::create()
		                                 ->getNewestMatching($instanceName, $stateChange->getSubscriptionId());

		// EPG grab subscriptions are not stored so we don't want to log these with a high level
		if ($subscription === null)
		{
			$this->logger
				->error('Got subscription stop without a matching start (instance: {instanceName}, subscription: {subscriptionId})',
					[
						'instanceName'   => $instanceName,
						'subscriptionId' => $stateChange->getSubscriptionId(),
					]);

			return;
		}

		$subscription->setStopped(new \DateTime());
		$subscription->save();

		$user    = $subscription->getUser();
		$channel = $subscription->getChannel();

		$this->logger
			->info('Stored subscription stop (instance: {instanceName}, user: {userName}, channel: {channelName})',
				[
					'instanceName' => $instanceName,
					'userName'     => $user !== null ? $user->getName() : 'N/A',
					'channelName'  => $channel->getName(),
				]);
	}


	/**
	 * @param PersistInputErrorEvent $event
	 */
	public function onInputError(PersistInputErrorEvent $event)
	{
		$input            = $event->getInput();
		$cumulativeErrors = $event->getCumulativeErrors();

		$inputError = new InputError();
		$inputError->setInput($input);
		$inputError->setFromInputErrorCumulative($cumulativeErrors);
		$inputError->save();
		
		$this->logger->debug('Persisted input errors (instance: {instanceName}, input: {friendlyName})', [
			'instanceName' => $input->getInstanceName(),
			'friendlyName' => $input->getFriendlyName(),
		]);
	}


	/**
	 * @param string $instanceName
	 * @param string $userName
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	private function onUserSeen($instanceName, $userName)
	{
		if (UserQuery::create()->hasUser($instanceName, $userName))
			return;

		$user = new User();
		$user->setInstanceName($instanceName)->setName($userName);
		$user->save();

		$this->logger->info('Stored new user (instance: {instanceName}, username: {userName})', [
			'instanceName' => $instanceName,
			'userName'     => $userName,
		]);
	}


	/**
	 * @param string $instanceName
	 * @param string $channelName
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	private function onChannelSeen($instanceName, $channelName)
	{
		if (ChannelQuery::create()->hasChannel($instanceName, $channelName))
			return;

		$channel = new Channel();
		$channel->setInstanceName($instanceName)->setName($channelName);
		$channel->save();

		$this->logger
			->info('Stored new channel (instance: {instanceName}, name: {channelName})', [
				'instanceName' => $instanceName,
				'channelName'  => $channelName,
			]);
	}

}
