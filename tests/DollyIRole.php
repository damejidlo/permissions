<?php

namespace Damejido\ACL\Tests;

use Nette\Security\IRole;
use Nette\SmartObject;



class DollyIRole implements IRole
{

	use SmartObject;

	public const ROLE_ID = 'dolly';



	/**
	 * @inheritdoc
	 */
	public function getRoleId()
	{
		return self::ROLE_ID;
	}

}
