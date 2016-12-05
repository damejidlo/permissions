<?php
declare(strict_types = 1);

/**
 * @testCase
 */

namespace Damejido\ACL\Tests;

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/ArticleResource.php';
require_once __DIR__ . '/MockIUserTrait.php';
require_once __DIR__ . '/DollyIRole.php';
require_once __DIR__ . '/DummyIRole.php';

use Damejidlo\ACL\Authorizator;
use Damejidlo\ACL\IUser;
use Damejidlo\ACL\ResourceDoesNotExists;
use Damejidlo\ACL\RoleDoesNotExists;
use Mockery;
use Tester\Assert;
use Tester\TestCase;



class AuthorizatorTest extends TestCase
{

	use MockIUserTrait;

	const ROLE_WRITER = 'writer';
	const ARTICLE_WRITE = 'write';



	public function testSimpleAllow()
	{
		$authorizator = $this->getAuthorizator();
		$authorizator->allow(self::ROLE_WRITER, ArticleResource::RESOURCE_ID, self::ARTICLE_WRITE);

		Assert::true($authorizator->isAllowed(
			$this->mockUser([self::ROLE_WRITER]),
			ArticleResource::RESOURCE_ID,
			self::ARTICLE_WRITE
		));
	}



	public function testSimpleCallbackAllow()
	{
		$authorizator = $this->getAuthorizator();

		$assertion = function (IUser $user, $queriedRole, $queriedResource) {
			/** @var ArticleResource $queriedResource */
			return $queriedResource->getId() === 5;
		};

		$authorizator->allow(self::ROLE_WRITER, ArticleResource::RESOURCE_ID, self::ARTICLE_WRITE, $assertion);

		Assert::true($authorizator->isAllowed(
			$this->mockUser([self::ROLE_WRITER]),
			new ArticleResource(5),
			self::ARTICLE_WRITE
		));

		Assert::false($authorizator->isAllowed(
			$this->mockUser([self::ROLE_WRITER]),
			new ArticleResource(42),
			self::ARTICLE_WRITE
		));
	}



	public function testSimpleNotAllowed()
	{
		$authorizator = new Authorizator();
		$authorizator->addRole('foo');
		$authorizator->addResource('bar');

		Assert::false($authorizator->isAllowed($this->mockUser([]), 'bar', 'baz'));
		Assert::false($authorizator->isAllowed($this->mockUser(['foo']), 'bar', 'baz'));
		$authorizator->allow('foo', 'bar', 'not-baz');
		Assert::false($authorizator->isAllowed($this->mockUser(['foo']), 'bar', 'baz'));
	}



	public function testDenySimple()
	{
		$authorizator = $this->getAuthorizator();
		$authorizator->allow(self::ROLE_WRITER, ArticleResource::RESOURCE_ID, ['foo', 'bar']);
		$authorizator->deny(self::ROLE_WRITER, ArticleResource::RESOURCE_ID, ['foo']);

		Assert::false($authorizator->isAllowed(
			$this->mockUser([self::ROLE_WRITER]),
			ArticleResource::RESOURCE_ID,
			'foo'
		));

		Assert::true($authorizator->isAllowed(
			$this->mockUser([self::ROLE_WRITER]),
			ArticleResource::RESOURCE_ID,
			'bar'
		));
	}



	public function testDoesNotExists()
	{
		Assert::exception(function () {
			$authorizator = new Authorizator();
			$authorizator->allow('foo', 'bar', 'baz');
		}, RoleDoesNotExists::class);

		Assert::exception(function () {
			$authorizator = new Authorizator();
			$authorizator->addRole('foo');
			$authorizator->allow('foo', 'bar', 'baz');
		}, ResourceDoesNotExists::class);
	}



	public function testIRoleObject()
	{
		$dollyRole = new DollyIRole();
		$dummyRole = new DummyIRole();
		$resource = new ArticleResource(9);

		$authorizator = new Authorizator();
		$authorizator->addRole($dollyRole);
		$authorizator->addRole($dummyRole, [$dollyRole]);
		$authorizator->addResource($resource);

		$authorizator->allow($dollyRole, ArticleResource::RESOURCE_ID, 'view');

		Assert::true($authorizator->isAllowed($this->mockUser([$dollyRole]), $resource, 'view'));

		/**
		 * IMPORTANT: In current implementation we do not support role inheritance for evaluating diretives.
		 *            Because of this, next assertions is false.
		 */
		Assert::false($authorizator->isAllowed($this->mockUser([$dummyRole]), $resource, 'view'));
	}



	/**
	 * @return Authorizator
	 */
	private function getAuthorizator() : Authorizator
	{
		$authorizator = new Authorizator();
		$authorizator->addRole(self::ROLE_WRITER);
		$authorizator->addResource(ArticleResource::RESOURCE_ID);

		return $authorizator;
	}

}



(new AuthorizatorTest())->run();
