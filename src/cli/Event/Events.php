<?php

namespace Jalle19\StatusManager\Event;

/**
 * Holds a list of all available events
 *
 * @package   Jalle19\StatusManager\Event
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
final class Events
{

	const MAIN_LOOP_STARTING = 'mainLoop.starting';

	const INSTANCE_STATUS_UPDATES        = 'status.instanceUpdates';
	const INSTANCE_STATE_REACHABLE       = 'status.instanceReachable';
	const INSTANCE_STATE_UNREACHABLE     = 'status.instanceUnreachable';
	const INSTANCE_STATE_MAYBE_REACHABLE = 'status.instanceMaybeReachable';

	const INSTANCE_SEEN             = 'persistence.instanceSeen';
	const CONNECTION_SEEN           = 'persistence.connectionSeen';
	const INPUT_SEEN                = 'persistence.inputSeen';
	const SUBSCRIPTION_SEEN         = 'persistence.subscriptionSeen';
	const SUBSCRIPTION_STATE_CHANGE = 'persistence.subscriptionStateChange';

}
