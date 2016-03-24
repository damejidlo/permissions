<?php

namespace Damejido\ACL\Tests;

use Nette\Object;
use Nette\Security\IRole;



class DummyIRole extends Object implements IRole
{

	const ROLE_ID = 'dummy';



	/**
	 * @inheritdoc
	 */
	public function getRoleId()
	{
		return self::ROLE_ID;
	}

}

