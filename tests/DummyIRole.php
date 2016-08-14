<?php
declare(strict_types = 1);

namespace Damejido\ACL\Tests;

use Nette\Object;
use Nette\Security\IRole;



class DummyIRole extends Object implements IRole
{

	const ROLE_ID = 'dummy';



	/**
	 * @inheritdoc
	 */
	public function getRoleId() : string
	{
		return self::ROLE_ID;
	}

}

