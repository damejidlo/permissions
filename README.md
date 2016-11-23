[![Downloads this Month](https://img.shields.io/packagist/dm/damejidlo/permissions.svg)](https://packagist.org/packages/damejidlo/permissions)
[![Latest Stable Version](https://poser.pugx.org/damejidlo/permissions/v/stable)](https://github.com/damejidlo/permissions/releases)
![](https://travis-ci.org/damejidlo/permissions.svg?branch=master)
![](https://scrutinizer-ci.com/g/damejidlo/permissions/badges/quality-score.png?b=master)
![](https://scrutinizer-ci.com/g/damejidlo/permissions/badges/coverage.png?b=master)

# Motivation
Purpose of this library is to add User specific data to `isAllowed` evaluation. Assertion callback got
`IUser` directly as first argument.

This solves biggest "problem" of native ACL in Nette such is:
```php
	$callback = function (IUser $user, $queriedRole, $queriedResource) {
		return $user->getEntity()->getId() === $queriedResource->getEntity()->getCreatorId();
	};

	// god can destroy world, but only the one he created
	$authorizator->allow('god', 'world', 'destroy', $callback);
```

Another aspect of this library is separating Authorizator from `Nette\Security\User` as
it's definitely not users responsibility to provide this functionality.

# Disclaimer
This library is written to be as much as possible similar to `Permission` class in Nette. However evaluation of rules
is written from scratch.

And therefore:
* does not implement `Nette\Security\IAuthorizator` (it can't due to different `isAllowed` method API),
* can be significantly **slower** (but is written nicely),
* there is no guarantee that behaves 100% same way.

# Installation
```
composer require damejidlo/permission
```

# Configuration
## `AccessList` and `Neon`
Example implementation of your own `AccessList` service.
```php
class AccessList extends Authorizator
{
	/**
	 * @param string[][] $roles
	 */
	public function addRoles(array $roles)
	{
		foreach ($roles as $role => $parentRoles) {
			$this->addRole($role, $parentRoles);
		}
	}

	/**
	 * @param @param string[] $resources
	 */
	public function addResources(array $resources)
	{
		foreach ($resources as $resource) {
			$this->addResource($resource);
		}
	}

	/**
	 * @param string[][][] $directives
	 */
	public function addDirectives(array $directives)
	{
		foreach ($directives as $resource => $resourceDirectives) {
			foreach ($resourceDirectives as $privilege => $privilegeDirectives) {
				foreach ($privilegeDirectives as $roleIdentifier => $directiveType) {
					$this->createDirective($directiveType, $roleIdentifier, $resource, $privilege);
				}
			}
		}
	}

	public function someStuff()
	{
		$callback = function (IUser $user, $queriedRole, $queriedResource) {
			return $user->getEntity()->getId() === $queriedResource->getEntity()->getCreatorId();
		};

		// god can destroy world, but only the one he created
		$authorizator->allow('god', 'world', 'destroy', $callback);
	}
}
```

Then just add to your `config.neon`
```yaml
parameters:
	acl:
		roles:
			writer: []
			reviewer: [writer]

		resources:
			- article

		directives:
			article:
				create:
					writer: allow
				publish:
					reviewer: allow

services:
	acl:
		class: YourProject\Security\AccessList
		setup:
			- addRoles(%acl.roles%)
			- addResources(%acl.resources%)
			- addDirectives(%acl.directives%)
			- someStuff() # here we can do some "cool stuff"
```

## Create your `AclUser`
```php
class AclUser extends Object implements IUser
{
	// Implement `getRoles` method
}
```

## Creating your own `Nette\Security\User`
You need to create your own `User` service
```php
class MyLoggedUser extends \Nette\Security\User
{
	/**
	 * @param IUserStorage $storage
	 * @param IAuthenticator|NULL $authenticator
	 */
	public function __construct(IUserStorage $storage, IAuthenticator $authenticator = NULL)
	{
		parent::__construct($storage, $authenticator); // No IAuthorizator here !!!
	}

	/**
	 * @inheritdoc
	 */
	public function isAllowed($resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL)
	{
		throw new LogicException('Use Damejidlo\ACL\Authorizator directly. User shouldn\'t have such a responsibility');
	}

	/**
	 * @inheritdoc
	 */
	public function isInRole($role)
	{
		throw new LogicException('Use Damejidlo\ACL\Authorizator directly. User shouldn\'t have such a responsibility');
	}

	/**
	 * @return AclUser
	 */
	public function getAclUser()
	{
		$entity = $this->getEntity(); // depens on your implementation
		return new AclUser($entity, $this->getRoles());
	}
}
```
```yaml
services:
    user: Some\Namespace\MyLoggedUser
```

## Load your Authorizator into template
Best way is to create your own `TemplateFactory`. And in `createTemplate` method just call:
```php
	/**
	 * @param Control|NULL $control
	 * @return Template
	 */
	public function createTemplate(Control $control = NULL)
	{
		$template = parent::createTemplate($control);

		// Some stuff (helper registration, etc...)

		$template->setParameters([
			'authorizator' => $this->authorizator,
		]);

		return $template;
	}
```

# Usage
And now, profit!
```php
	// In some Presenter

	public function handleDestroy($worldId)
	{
		$world = $this->worldFinder->findWorld($worldId);
		$resource = new WorldResource($world);
		$permission = 'destroy';

		if (!$this->authorizator->isAllowed($this->user->getAclUser(), $resource, $permission) {
			throw new NotAllowedException($resource, $permission);
		}
	}
```
