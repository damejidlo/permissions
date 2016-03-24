<?php

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
	public static function stringifyResource($resource)
	{
		return $resource instanceof IResource ? $resource->getResourceId() : $resource;
	}



	/**
	 * @param IRole|string $role
	 * @return string
	 */
	public static function stringifyRole($role)
	{
		return $role instanceof IRole ? $role->getRoleId() : $role;
	}

}
