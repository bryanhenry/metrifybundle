# metrify
Simplify CRUD with Symfony, FOSRestBundle, and Doctrine.

## Synopsis

This package aims to reduce some of the boiler plate when doing simple crud with FOSRestBundle and Symfony forms, but is not intended for complex interactions.

## Usage

Create your entity, and implement the FormEntityInterface

```php
namespace MyNameSpace;

class MyEntity extends FormEntityInterface {

	protected $id;

	public function getId() : ?int
	{

	    return $this->id;

	}

}

```

Create your controller, and extend the AbstractCrudController abstract class.

```php

namespace MyNameSpace;

use BryanHenry\MetrifyBundle\Components\Controller\AbstractCrudController;
use MyNameSpace\MyEntity;
use MyNameSpace\MyEntityFormType;

/**
 * Set your prefix here, or through yml.
 * @RouteResource("item/definitions")
 */
class MyControllerController extends AbstractCrudController
{

    // Return the name of the form class here.
    protected function getFormName(): string
    {
        return MyEntityFormType::class;

    }

    // Return the name of the entity here, ensure that it implements the FormEntityInterface interface.
    protected function getEntityName(): string
    {
        return MyEntity::class;
    }

}

```

Inside of your entity's repository class, you must use the provided trait to provide pagination:

```php
namespace MyNameSpace;

use Doctrine\ORM\EntityRepository;
use BryanHenry\MetrifyBundle\Components\Repository\RepositoryPaginateTrait;

class MyEntityRepository extends EntityRepository
{

    use RepositoryPaginateTrait;

}
```

## Contributors

Bryan Henry <bryan@misterflow.com>

## License

MIT