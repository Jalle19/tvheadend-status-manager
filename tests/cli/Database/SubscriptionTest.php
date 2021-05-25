<?php

namespace Jalle19\StatusManager\Test\Database;

use Jalle19\StatusManager\Database\Subscription;
use Jalle19\tvheadend\model\multiplex\Multiplex;
use Jalle19\tvheadend\model\network\DvbcNetwork;
use Jalle19\tvheadend\model\network\DvbsNetwork;
use Jalle19\tvheadend\model\SubscriptionStatus;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{

	public function testParseNetworkAndMux(): void
	{
		$availableNetworks = [
			new DvbsNetwork('0.8W Thor'),
			new DvbsNetwork('0.8W Thor 5/6/7 Intelsat 10-02'),
			new DvbsNetwork('28.2E Astra'),
			new DvbcNetwork('DNA'),
		];

		$availableMuxes = [
			new Multiplex('226MHz'),
			new Multiplex('234MHz'),
			new Multiplex('12437H'),
			new Multiplex('12437V'),
			new Multiplex('242MHz'),
		];

		// Network name
		$subscriptionStatus          = new SubscriptionStatus();
		$subscriptionStatus->channel = 'TV8 HD';
		$subscriptionStatus->service = 'SAT>IP DVB-S Tuner #1 (10.110.5.11:9983@TCP)/0.8W Thor/12437H/TV8 HD';
		$this->assertEquals('0.8W Thor', Subscription::parseNetwork($subscriptionStatus, $availableNetworks));
		$this->assertEquals('12437H', Subscription::parseMux($subscriptionStatus, $availableMuxes));

		// Trickier network name
		$subscriptionStatus->service = 'SAT>IP DVB-S Tuner #1 (10.110.5.11:9983@TCP)/0.8W Thor 5/6/7 Intelsat 10-02/12437H/TV8 HD';
		$this->assertEquals('0.8W Thor 5/6/7 Intelsat 10-02',
			Subscription::parseNetwork($subscriptionStatus, $availableNetworks));

		// Tricky adapter name
		$subscriptionStatus->channel = 'MTV3 HD';
		$subscriptionStatus->service = 'TurboSight TBS 6281 DVB-T/T2/C #1 : DVB-C #0/DNA/226MHz/MTV3 HD';
		$this->assertEquals('DNA', Subscription::parseNetwork($subscriptionStatus, $availableNetworks));
		$this->assertEquals('226MHz', Subscription::parseMux($subscriptionStatus, $availableMuxes));
	}
}
