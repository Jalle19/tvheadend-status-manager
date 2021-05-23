<?php

namespace Jalle19\StatusManager\Test\Database;

use Jalle19\StatusManager\Database\Subscription;
use Jalle19\tvheadend\model\SubscriptionStatus;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{

	public function testParseNetworkAndMux(): void
	{
		$subscriptionStatus          = new SubscriptionStatus();
		$subscriptionStatus->channel = 'TV8 HD';
		$subscriptionStatus->service = 'SAT>IP DVB-S Tuner #1 (10.110.5.11:9983@TCP)/0.8W Thor/12437H/TV8 HD';

		$this->assertEquals('0.8W Thor', Subscription::parseNetwork($subscriptionStatus));
		$this->assertEquals('12437H', Subscription::parseMux($subscriptionStatus));
	}
}
