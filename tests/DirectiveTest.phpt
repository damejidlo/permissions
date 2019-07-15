<?php
declare(strict_types = 1);

/**
 * @testCase
 */

namespace Damejido\ACL\Tests;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/MockIUserTrait.php';

use Damejidlo\ACL\Directive;
use Damejidlo\ACL\IUser;
use Nette\SmartObject;
use Tester\Assert;
use Tester\TestCase;



class DirectiveTest extends TestCase
{

	use MockIUserTrait;
	use SmartObject;



	public function testDoesNotMatch() : void
	{
		$assertion = function (IUser $user, $queriedRole, $queriedResource) {
			/** @var ArticleResource $queriedResource */
			return $user->getEntity() === ['id' => 5];
		};

		$directive = new Directive(Directive::ALLOW, ['role-foo'], ['resource-bar'], ['privilege-baz'], $assertion);

		Assert::true($directive->evaluate($this->mockUser(['role-foo'], 5), 'resource-bar', 'privilege-baz'));

		Assert::false($directive->evaluate($this->mockUser(['role-foo'], 5), 'resource-bar', 'privilege-xxx'));
		Assert::false($directive->evaluate($this->mockUser(['role-foo'], 5), 'resource-xxx', 'privilege-baz'));
		Assert::false($directive->evaluate($this->mockUser(['role-xxx'], 5), 'resource-bar', 'privilege-baz'));
		Assert::false($directive->evaluate($this->mockUser(['role-foo'], 42), 'resource-bar', 'privilege-baz'));
	}

}



(new DirectiveTest())->run();
