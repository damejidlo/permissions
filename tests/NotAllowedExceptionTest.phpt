<?php
declare(strict_types = 1);

namespace Damejido\ACL\Tests;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/ArticleResource.php';

use Damejidlo\ACL\NotAllowedException;
use Mockery;
use Tester\Assert;
use Tester\TestCase;



class NotAllowedExceptionTest extends TestCase
{

	public function testAll()
	{
		$resource = new ArticleResource(9);
		$exception = new NotAllowedException($resource, 'edit');
		Assert::equal('edit', $exception->getPrivilege());
		Assert::same($resource, $exception->getResource());
	}

}



(new NotAllowedExceptionTest())->run();
