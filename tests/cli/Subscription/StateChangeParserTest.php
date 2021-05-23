<?php

namespace Jalle19\StatusManager\Test\Subscription;

use Jalle19\StatusManager\Subscription\StateChange;
use Jalle19\StatusManager\Subscription\StateChangeParser;
use Jalle19\tvheadend\model\comet\LogMessageNotification;
use PHPUnit\Framework\TestCase;

/**
 * Class StateChangeParserTest
 * @package   Jalle19\StatusManager\Test\Subscription
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class StateChangeParserTest extends TestCase
{

	/**
	 * @param string $message
	 * @param int    $expectedSubscriptionId
	 * @param string $expectedState
	 * @param string $expectedJson
	 *
	 * @dataProvider messageProvider
	 */
	public function testParseMessage($message, $expectedSubscriptionId, $expectedState, $expectedJson)
	{
		$logMessage         = new LogMessageNotification();
		$logMessage->logtxt = $message;

		$stateChanges = StateChangeParser::parseStateChanges([$logMessage]);
		$stateChange  = $stateChanges[0];

		$this->assertEquals($expectedSubscriptionId, $stateChange->getSubscriptionId());
		$this->assertEquals($expectedState, $stateChange->getState());
		$this->assertJson(json_encode($stateChange), $expectedJson);
	}


	/**
	 * @return array
	 */
	public function messageProvider()
	{
		return [
			[
				'2016-04-03 20:29:44.495 subscription: 296B: "HTTP" subscribing on channel "Axess TV", weight: 100, adapter: "IPTV", network: "foo", mux: "bar", provider: "Levira", service: "Axess TV", profile="pass", hostname="::ffff", client="VLC/2.2.1 LibVLC/2.2.1"',
				hexdec('296B'),
				StateChange::STATE_SUBSCRIPTION_STARTED,
				json_encode([hexdec('296B'), StateChange::STATE_SUBSCRIPTION_STARTED]),
			],
			[
				'2016-04-03 20:29:44.495 subscription: 296B: "HTTP" unsubscribing from "Axess TV", hostname="::ffff", client="VLC/2.2.1 LibVLC/2.2.1"',
				hexdec('296B'),
				StateChange::STATE_SUBSCRIPTION_STOPPED,
				json_encode([hexdec('296B'), StateChange::STATE_SUBSCRIPTION_STOPPED]),
			],
		];
	}

}
