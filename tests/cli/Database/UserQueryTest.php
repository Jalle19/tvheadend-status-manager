<?php

namespace Jalle19\StatusManager\Test\Database;

use Jalle19\StatusManager\Database\UserQuery;

/**
 * Class UserQueryTest
 * @package   Jalle19\StatusManager\Test\Database
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UserQueryTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 */
	public function testFilterIgnoredUsers()
	{
		/* @var \PHPUnit_Framework_MockObject_MockObject|UserQuery $mock */
		$mock = $this->getMockBuilder(UserQuery::class)
		             ->setMethods(['filterByName'])
		             ->getMock();

		// There should be four filter calls for three passed names, since the DVR user is added transparently
		$mock->expects($this->exactly(4))
		     ->method('filterByName');

		$ignoredUsers = [
			'foo',
			'bar',
			'baz',
		];

		$mock->filterIgnoredUsers($ignoredUsers);
	}

}
