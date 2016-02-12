<?php

namespace Jalle19\StatusManager;

use Jalle19\StatusManager\Database;
use Jalle19\StatusManager\Database\Channel;
use Jalle19\StatusManager\Database\ChannelQuery;
use Jalle19\StatusManager\Database\Connection;
use Jalle19\StatusManager\Database\ConnectionQuery;
use Jalle19\StatusManager\Database\Input;
use Jalle19\StatusManager\Database\InputQuery;
use Jalle19\StatusManager\Database\InstanceQuery;
use Jalle19\StatusManager\Database\Subscription;
use Jalle19\StatusManager\Database\SubscriptionQuery;
use Jalle19\StatusManager\Database\User;
use Jalle19\StatusManager\Database\UserQuery;
use Jalle19\StatusManager\Subscription\StateChange;
use Jalle19\tvheadend\model\ConnectionStatus;
use Jalle19\tvheadend\model\InputStatus;
use Jalle19\tvheadend\model\SubscriptionStatus;
use Jalle19\tvheadend\Tvheadend;
use Psr\Log\LoggerInterface;

/**
 * Handles persisting of objects to the database
 *
 * @package   Jalle19\StatusManager
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class PersistenceManager
{

	/**
	 * @var LoggerInterface
	 */
	private $_logger;


	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->_logger = $logger;
	}


	/**
	 * @param Tvheadend $instance
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function onInstanceSeen(Tvheadend $instance)
	{
		if ($this->hasInstance($instance))
			return;

		$instanceModel = new Database\Instance();
		$instanceModel->setPrimaryKey($instance->getHostname());
		$instanceModel->save();

		$this->_logger->info('Stored new instance {instanceName}', [
			'instanceName' => $instance->getHostname(),
		]);

		// Create a special user for eventual DVR subscriptions
		$user = new User();
		$user->setInstance($instanceModel);
		$user->setName(User::NAME_DVR);
		$user->save();

		$this->_logger->info('Stored new special user (instance: {instanceName}, user: {userName})', [
			'instanceName' => $instance->getHostname(),
			'userName'     => $user->getName(),
		]);
	}


	/**
	 * @param string           $instanceName
	 * @param ConnectionStatus $connectionStatus
	 */
	public function onConnectionSeen($instanceName, ConnectionStatus $connectionStatus)
	{
		if ($this->hasConnection($instanceName, $connectionStatus))
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

		$this->_logger->info('Stored new connection (instance: {instanceName}, peer: {peer})', [
			'instanceName' => $instanceName,
			'peer'         => $connectionStatus->peer,
		]);
	}


	/**
	 * @param string      $instanceName
	 * @param InputStatus $inputStatus
	 */
	public function onInputSeen($instanceName, InputStatus $inputStatus)
	{
		// Update the input and started fields for existing inputs
		if ($this->hasInput($inputStatus->uuid))
		{
			$input = InputQuery::create()->findPk($inputStatus->uuid);
			$input->setStarted(new \DateTime())->setWeight($inputStatus->weight);

			return;
		}

		$input = new Input();
		$input->setPrimaryKey($inputStatus->uuid);
		$input->setInstanceName($instanceName)
		      ->setStarted(new \DateTime())
		      ->setInput($inputStatus->input)
		      ->setWeight($inputStatus->weight)
		      ->setNetwork(Input::parseNetwork($inputStatus))
		      ->setMux(Input::parseMux($inputStatus))->save();

		$this->_logger->info('Stored new input (instance: {instanceName}, network: {network}, mux: {mux}, weight: {weight})',
			[
				'instanceName' => $instanceName,
				'network'      => $input->getNetwork(),
				'mux'          => $input->getMux(),
				'weight'       => $input->getWeight(),
			]);
	}


	/**
	 * @param string $instanceName
	 * @param string $userName
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function onUserSeen($instanceName, $userName)
	{
		if ($this->hasUser($instanceName, $userName))
			return;

		$user = new User();
		$user->setInstanceName($instanceName)->setName($userName);
		$user->save();

		$this->_logger->info('Stored new user (instance: {instanceName}, username: {userName})', [
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
	public function onChannelSeen($instanceName, $channelName)
	{
		if ($this->hasChannel($instanceName, $channelName))
			return;

		$channel = new Channel();
		$channel->setInstanceName($instanceName)->setName($channelName);
		$channel->save();

		$this->_logger->info('Stored new channel (instance: {instanceName}, name: {channelName})', [
			'instanceName' => $instanceName,
			'channelName'  => $channelName,
		]);
	}


	/**
	 * @param string             $instanceName
	 * @param SubscriptionStatus $status
	 *
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function onSubscriptionSeen($instanceName, SubscriptionStatus $status)
	{
		// Ignore EPG grabber subscriptions
		if ($status->getType() === SubscriptionStatus::TYPE_EPGGRAB)
			return;

		// Determine the username to store for the subscription
		$username = $status->username;

		switch ($status->getType())
		{
			case SubscriptionStatus::TYPE_RECORDING:
				$username = 'dvr';
				break;
		}

		// Get the instance, user and channel
		$instance = InstanceQuery::create()->findPk($instanceName);
		$user     = UserQuery::create()->filterByInstance($instance)->filterByName($username)->findOne();

		// Ensure the channel exists
		$this->onChannelSeen($instanceName, $status->channel);
		$channel = ChannelQuery::create()->filterByInstance($instance)->filterByName($status->channel)->findOne();

		if ($this->hasSubscription($instance, $user, $channel, $status))
			return;

		// Determine which input the subscription uses
		$input = InputQuery::create()->filterBySubscriptionStatus($instanceName, $status)->findOne();

		if ($input === null)
		{
			$this->_logger->warning('Got subscription that cannot be tied to an input ({instanceName}, user: {userName}, channel: {channelName})',
				[
					'instanceName' => $instanceName,
					'userName'     => $user !== null ? $user->getName() : 'N/A',
					'channelName'  => $channel->getName(),
				]);

			return;
		}

		$subscription = new Subscription();
		$subscription->setInstance($instance)->setInput($input)->setUser($user)->setChannel($channel)
		             ->setSubscriptionId($status->id)->setStarted($status->start)->setTitle($status->title)
		             ->setService($status->service);
		$subscription->save();

		$this->_logger->info('Stored new subscription (instance: {instanceName}, user: {userName}, channel: {channelName})',
			[
				'instanceName' => $instanceName,
				'userName'     => $user !== null ? $user->getName() : 'N/A',
				'channelName'  => $channel->getName(),
			]);
	}


	/**
	 * @param string      $instanceName
	 * @param StateChange $stateChange
	 */
	public function onSubscriptionStateChange($instanceName, StateChange $stateChange)
	{
		// We only need to persist subscription stops
		if ($stateChange->getState() === StateChange::STATE_SUBSCRIPTION_STARTED)
			return;

		// Find the latest matching subscription
		$subscription = SubscriptionQuery::create()->filterByInstanceName($instanceName)
		                                 ->filterBySubscriptionId($stateChange->getSubscriptionId())
		                                 ->addDescendingOrderByColumn('started')->findOne();

		// EPG grab subscriptions are not stored so we don't want to log these with a high level
		if ($subscription === null)
		{
			$this->_logger->debug('Got subscription stop without a matching start (instance: {instanceName}, subscription: {subscriptionId})',
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

		$this->_logger->info('Stored subscription stop (instance: {instanceName}, user: {userName}, channel: {channelName})',
			[
				'instanceName' => $instanceName,
				'userName'     => $user !== null ? $user->getName() : 'N/A',
				'channelName'  => $channel->getName(),
			]);
	}


	/**
	 * @param Tvheadend $instance
	 *
	 * @return bool whether the instance exists in the database
	 */
	private function hasInstance(Tvheadend $instance)
	{
		return InstanceQuery::create()->findPk($instance->getHostname()) !== null;
	}


	/**
	 * @param                  $instanceName
	 * @param ConnectionStatus $connectionStatus
	 *
	 * @return bool whether the connection exists in the database
	 */
	private function hasConnection($instanceName, ConnectionStatus $connectionStatus)
	{
		return ConnectionQuery::create()->filterByInstanceName($instanceName)->filterByPeer($connectionStatus->peer)
		                      ->filterByStarted($connectionStatus->started)->findOne() !== null;
	}


	/**
	 * @param string $uuid
	 *
	 * @return bool
	 */
	private function hasInput($uuid)
	{
		return InputQuery::create()->findPk($uuid) !== null;
	}


	/**
	 * @param string $instanceName
	 * @param string $userName
	 *
	 * @return bool
	 */
	private function hasUser($instanceName, $userName)
	{
		return UserQuery::create()->filterByInstanceName($instanceName)->filterByName($userName)->findOne() !== null;
	}


	/**
	 * @param string $instanceName
	 * @param string $channelName
	 *
	 * @return bool
	 */
	private function hasChannel($instanceName, $channelName)
	{
		return ChannelQuery::create()->filterByInstanceName($instanceName)->filterByName($channelName)
		                   ->findOne() !== null;
	}


	/**
	 * @param Database\Instance  $instance
	 * @param User|null          $user
	 * @param Channel            $channel
	 * @param SubscriptionStatus $subscription
	 *
	 * @return bool
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	private function hasSubscription(
		Database\Instance $instance,
		$user,
		Channel $channel,
		SubscriptionStatus $subscription
	) {
		// Not all subscriptions are tied to a user
		$userId = $user !== null ? $user->getId() : null;

		return SubscriptionQuery::create()->filterByInstance($instance)->filterByUserId($userId)
		                        ->filterByChannel($channel)
		                        ->filterBySubscriptionId($subscription->id)->filterByStarted($subscription->start)
		                        ->findOne() !== null;
	}

}
