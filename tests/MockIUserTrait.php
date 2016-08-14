<?php
declare(strict_types = 1);

namespace Damejido\ACL\Tests;

use Damejidlo\ACL\IUser;
use Mockery;
use Mockery\MockInterface;
use Nette\Security\IRole;



trait MockIUserTrait
{

	/**
	 * @param IRole[]|string[] $roles $roles
	 * @param int $userId
	 * @return IUser|MockInterface
	 */
	private function mockUser(array $roles, $userId = 0)
	{
		return Mockery::mock(IUser::class)
			->shouldReceive('getRoles')->andReturn($roles)->getMock()
			->shouldReceive('getEntity')->andReturn(['id' => $userId])->getMock();
	}
}
