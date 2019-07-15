<?php

namespace Damejido\ACL\Tests;

use Nette\Security\IRole;
use Nette\SmartObject;



class DummyIRole implements IRole
{

	use SmartObject;

	private const ROLE_ID = 'dummy';



	/**
	 * @inheritdoc
	 */
	public function getRoleId()
	{
		return self::ROLE_ID;
	}

}
