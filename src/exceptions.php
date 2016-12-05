<?php
declare(strict_types = 1);

namespace Damejidlo\ACL;

use Exception;
use Nette\Security\IResource;
use RuntimeException;



class RoleDoesNotExists extends RuntimeException
{

}



class ResourceDoesNotExists extends RuntimeException
{

}



class NotAllowedException extends Exception
{

	/**
	 * @var IResource
	 */
	private $resource;

	/**
	 * @var string
	 */
	private $privilege;



	/**
	 * @param IResource $resource
	 * @param string $privilege
	 */
	public function __construct(IResource $resource, string $privilege)
	{
		$this->resource = $resource;
		$this->privilege = $privilege;
	}



	/**
	 * @return IResource
	 */
	public function getResource() : IResource
	{
		return $this->resource;
	}



	/**
	 * @return string
	 */
	public function getPrivilege() : string
	{
		return $this->privilege;
	}

}
