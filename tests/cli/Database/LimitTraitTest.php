<?php

namespace Jalle19\StatusManager\Test\Database;

use Jalle19\StatusManager\Database\LimitTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class LimitTraitTest
 * @package   Jalle19\StatusManager\Test\Database
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://www.gnu.org/licenses/gpl.html The GNU General Public License v2.0
 */
class LimitTraitTest extends TestCase
{

	/**
	 * Tests that limit() is actually called correctly
	 */
	public function testTrait()
	{
		/* @var \PHPUnit_Framework_MockObject_MockObject|LimitTrait $mock */
		$mock = $this->getMockForTrait('\Jalle19\StatusManager\Database\LimitTrait');

		$mock->expects($this->once())
		     ->method('limit');

		$mock->filterByLimit(10);

		$mock->expects($this->never())
		     ->method('limit');

		$mock->filterByLimit(null);
	}

}
