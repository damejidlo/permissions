<?php
declare(strict_types = 1);

namespace Damejidlo\ACL;

use Nette\Object;
use Nette\Security\IResource;
use Nette\Security\IRole;



class Stringify extends Object
{

	/**
	 * @param IResource|string $resource
	 * @return string
	 */
	public static function stringifyResource($resource) : string
	{
		return $resource instanceof IResource ? $resource->getResourceId() : $resource;
	}



	/**
	 * @param IRole|string $role
	 * @return string
	 */
	public static function stringifyRole($role) : string
	{
		return $role instanceof IRole ? $role->getRoleId() : $role;
	}

}
