<?php
declare(strict_types = 1);

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
	public function __construct(int $id)
	{
		$this->id = $id;
	}



	/**
	 * @inheritdoc
	 */
	public function getResourceId() : string
	{
		return self::RESOURCE_ID;
	}



	/**
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

}
