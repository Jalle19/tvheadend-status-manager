<?php

namespace Jalle19\StatusManager\Manager;

use Jalle19\ReactHttpStatic\Authentication\Handler\Basic as BasicAuthenticationHandler;
use Jalle19\ReactHttpStatic\StaticWebServer;
use Jalle19\StatusManager\Configuration\Configuration;
use Jalle19\StatusManager\Event\Events;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class HttpRequestManager
 * @package   Jalle19\StatusManager\Manager
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class HttpRequestManager extends AbstractManager implements EventSubscriberInterface
{

	/**
	 * @var StaticWebServer
	 */
	private $_staticWebServer;


	/**
	 * @inheritdoc
	 */
	public function __construct(
		Configuration $configuration,
		LoggerInterface $logger,
		EventDispatcher $eventDispatcher,
		LoopInterface $loop
	) {
		parent::__construct($configuration, $logger, $eventDispatcher);

		$socket = new SocketServer($loop);
		$socket->listen($configuration->getHttpListenPort(), $configuration->getListenAddress());

		// Configure the web server
		$webroot                = realpath(__DIR__ . '/../../client/app');
		$this->_staticWebServer = new StaticWebServer(new HttpServer($socket), $webroot, $logger);

		// Configure the authentication handler
		$this->_staticWebServer->setAuthenticationHandler(
			new BasicAuthenticationHandler('tvheadend-status-manager', [$this, 'validateCredentials']));
	}


	/**
	 * @inheritdoc
	 */
	public static function getSubscribedEvents()
	{
		return [
			Events::MAIN_LOOP_STARTING => 'onMainLoopStarted',
		];
	}


	/**
	 * Called when the main loop has started
	 */
	public function onMainLoopStarted()
	{
		$this->logger->notice('Starting the HTTP server on {address}:{port}', [
			'address' => $this->configuration->getListenAddress(),
			'port'    => $this->configuration->getHttpListenPort(),
		]);
	}


	/**
	 * Checks whether the supplied username and password matches the configuration
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 */
	public function validateCredentials($username, $password)
	{
		return $username === $this->configuration->getHttpUsername() && $password === $this->configuration->getHttpPassword();
	}

}
