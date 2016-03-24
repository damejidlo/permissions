<?php

namespace Damejido\ACL\Tests;

use Nette\Object;
use Nette\Security\IResource;



class ArticleResource extends Object implements IResource
{

	const RESOURCE_ID = 'article';

	/**
	 * @var int
	 */
	private $id;



	/**
	 * @param int $id
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}



	/**
	 * @inheritdoc
	 */
	public function getResourceId()
	{
		return self::RESOURCE_ID;
	}



	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}
