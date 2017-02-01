# Amber
A dependency-injection framework consisting of Models, Services, Views, and Events

## Overview
Amber is a dependency-injection framework that further breaks down the Controller aspect of the popular Model-View-Controller pattern into separate entities that promotes reusability and testability.

## Events
Events are user actions that alter the state of the application, generally by URL navigation or submitting data (GET, POST, PUT, etc).

Creating an event namespace, in this case a "global" namespace, that requires a user to be logged in, a "requires login" policy, would be defined like below.

```php
  $resinite->router->setNamespace('','/',function($router){

    $router->addValues(array(
      'policies' => [
          'requires_login' => 'unauthorized'
        ]
    ));

  	$router->add('index','')->addValues(
  		array(
  			'service'=>'index',
  			'format'=>'html'
  		)
  	);

  });
```

This particular route matches a blank URL and gives it the alias 'index.' If the policy is validated, the service "index" will be executed. If the policy fails, an unauthorized responder will execute. 

## Policies
A policy enforces a specific condition before a service will execute. In our route, we want to enforce that a user is logged in. A "RequiresLoginPolicy" class has to be defined with a resolve method.

```php
namespace Application\Policies;

class RequiresLoginPolicy extends \Amber\Policy\Resolvable {

  public function resolve($current_user) : bool {
    return $current_user;
  }

}
```

The `current_user` parameter will be an injected object, a `resolver` once executed. 

## Resolvers
Resolvers are custom objects that can be injected into policies, resolvers, and services. These can be custom defined objects or defaults defined in your application.

```php
namespace Application\Resolvers;

class CurrentUser {
  public function __construct() {}

  public function resolve($session) {
    // find user from a session and return the found user or null
    return $this;
  }

}
```

## Services
Services handle the user action. This is essentially a bare-bones `Controller` in the MVC pattern, with an `execute` method. The `params` parameter contains the URL parameters specified in the `events` (note this is subject to change to being a service property). You can also require any resolved policy results, in this case, the result from the `CurrentUser` resolver.

```php
namespace Application\Services;

class IndexService
{
  public function execute($current_user, $params) : void
  {
    // do something with the current user and params
  }
}
```

## Responders
