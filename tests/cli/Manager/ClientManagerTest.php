<?php

namespace Jalle19\StatusManager\Test\Manager;

use Jalle19\StatusManager\Event\Events;
use Jalle19\StatusManager\Event\InstanceStatusUpdatesEvent;
use Jalle19\StatusManager\Instance\InstanceStatusCollection;
use Jalle19\StatusManager\Message\Request\AuthenticationRequest;
use Jalle19\StatusManager\Message\Response\AuthenticationResponse;
use Ratchet\ConnectionInterface;
use React\EventLoop\Factory as EventLoopFactory;

/**
 * Class ClientManagerTest
 * @package   Jalle19\StatusManager\Test\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class ClientManagerTest extends AbstractManagerTest
{

	/**
	 * @var DummyClientManager
	 */
	private $_manager;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|ConnectionInterface
	 */
	private $_clientMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|ConnectionInterface
	 */
	private $_anotherClientMock;


	/**
	 * @inheritdoc
	 */
	protected function setUp()
	{
		$this->_manager = new DummyClientManager($this->configuration, $this->logger, $this->eventDispatcher,
			EventLoopFactory::create());

		// Create two clients and connect them
		$this->_clientMock = $this->getMockBuilder(ConnectionInterface::class)
		                          ->setMethods(['send', 'close'])
		                          ->getMock();

		$this->_anotherClientMock = clone $this->_clientMock;

		// Connect the clients
		$this->_manager->onOpen($this->_clientMock);
		$this->_manager->onOpen($this->_anotherClientMock);

		// Wire the event and message handling
		$this->eventDispatcher->addSubscriber($this->_manager);
		$this->_manager->registerMessageHandler($this->_manager);
	}


	/**
	 * Tests that clients are properly registered as connected
	 */
	public function testConnectedClients()
	{
		$this->assertEquals(2, $this->_manager->getConnectedClients()->count());
	}


	/**
	 * Tests that clients can only be authenticated with the correct access token
	 */
	public function testAuthentication()
	{
		// Authenticate the first client, check for success
		$response = $this->_manager->handleMessage(
			new AuthenticationRequest($this->configuration->getAccessToken()),
			$this->_clientMock);

		/* @var AuthenticationResponse $response */
		$this->assertEquals(AuthenticationResponse::STATUS_SUCCESS, $response->getStatus());

		// Authenticate the second client, check for failure
		$response = $this->_manager->handleMessage(
			new AuthenticationRequest('very invalid token'),
			$this->_anotherClientMock);

		/* @var AuthenticationResponse $response */
		$this->assertEquals(AuthenticationResponse::STATUS_FAILURE, $response->getStatus());

		$this->assertEquals(1, $this->_manager->getAuthenticatedClients()->count());
	}


	/**
	 * Tests that instance status updates are broadcast to all authenticated clients and not to those that are just
	 * connected but not authenticated
	 */
	public function testHandleStatusUpdates()
	{
		$this->_manager->handleMessage(
			new AuthenticationRequest($this->configuration->getAccessToken()),
			$this->_clientMock);

		$this->_clientMock->expects($this->once())->method('send');
		$this->_anotherClientMock->expects($this->never())->method('send');

		$this->eventDispatcher->dispatch(Events::INSTANCE_STATUS_UPDATES, new InstanceStatusUpdatesEvent(
			new InstanceStatusCollection()
		));
	}


	/**
	 * Tests that requests from clients are sent back as responses
	 */
	public function testMessageHandling()
	{
		$authenticationRequest = new AuthenticationRequest($this->configuration->getAccessToken());

		// Authenticate the client
		$this->_manager->handleMessage($authenticationRequest, $this->_clientMock);

		$expectedResponse = new AuthenticationResponse(AuthenticationResponse::STATUS_SUCCESS);
		$this->_clientMock->expects($this->once())->method('send')->with(json_encode($expectedResponse));

		$this->_manager->onMessage($this->_clientMock, json_encode($authenticationRequest));
	}

}
