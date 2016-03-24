<?php

namespace Damejido\ACL\Tests;

use Nette\Object;
use Nette\Security\IRole;



class DollyIRole extends Object implements IRole
{

	const ROLE_ID = 'dolly';



	/**
	 * @inheritdoc
	 */
	public function getRoleId()
	{
		return self::ROLE_ID;
	}

}

