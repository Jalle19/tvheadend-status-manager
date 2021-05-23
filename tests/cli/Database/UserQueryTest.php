<?php

namespace Jalle19\StatusManager\Test\Database;

use Jalle19\StatusManager\Database\UserQuery;
use PHPUnit\Framework\TestCase;

/**
 * Class UserQueryTest
 * @package   Jalle19\StatusManager\Test\Database
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class UserQueryTest extends TestCase
{

	/**
	 * There should be four filter calls for three passed names, since the DVR user is added transparently
	 */
	public function testFilterIgnoredUsers()
	{
		$mock = $this->getMockedUserQuery();
		$mock->expects($this->exactly(4))
		     ->method('filterByName');

		$ignoredUsers = [
			'foo',
			'bar',
			'baz',
		];

		$mock->filterIgnoredUsers($ignoredUsers);
	}


	/**
	 * When no ignored users are specified, only the DVR user should be added
	 */
	public function testFilterIgnoredUsersNone()
	{
		$mock = $this->getMockedUserQuery();
		$mock->expects($this->once())
		     ->method('filterByName');

		$mock->filterIgnoredUsers([]);
	}


	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|UserQuery
	 */
	private function getMockedUserQuery()
	{
		return $this->getMockBuilder(UserQuery::class)
		            ->setMethods(['filterByName'])
		            ->getMock();
	}

}
