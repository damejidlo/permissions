<?php

namespace Damejido\ACL\Tests;

require_once __DIR__ . '/bootstrap.php';

use Tester\Assert;
use Tester\TestCase;



/**
 * @testCase
 */
class DummyTest extends TestCase
{

	public function testDummy()
	{
		Assert::true((new \Dummy())->foo());
	}

}



(new DummyTest())->run();





