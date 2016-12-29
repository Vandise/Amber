<?php

namespace Amber\Injection\Resolve;

/**
 * Policy Injection Resolver
 *
 * Resolves policies by injecting objects in the "resolve" method
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Injection\Resolve
 * @since Nov 4th, 2015
 * @version v0.1
 */
class Policy extends \Amber\Injection\Injectable {

  public function __construct($instance) {
    $this->instance = $instance;
    $this->method = 'resolve';
    $params = (new \ReflectionMethod($instance, 'resolve'))->getParameters();
    foreach($params as $param) {
      array_push($this->parameters, $param->getName());
    }
  }

  public function inject(array $overrides) {
    $parameters = array();
    foreach($this->parameters as $paramName) {
      if (array_key_exists($paramName, $overrides)) {
        array_push($parameters, $overrides[$paramName]);
      } else {
        array_push($parameters, $this->resolveDepenency($paramName));
      }
    }
    return call_user_func_array(array($this->instance, $this->method), $parameters);
  }

  public function getInstance() {
    return $this->instance;
  }

  private function resolveDepenency($name) {
    $class = null;
    $name = $this->className($name);
    if (array_key_exists($name, $this->instance->getResolvePaths())) {
      $class = $this->instance->getResolvePaths()[$name].$name;
    } else {
      $class = '\Application\Resolvers\\'.$name;
    }

    // application resolvers
    if (!class_exists($class)) {
      // custom objects
      if (!class_exists($name)) {
        throw new \Exception("Resolver $class was not found");
      } else {
        throw new \Exception("Custom resolver objects are not yet implemented");
        //$class = new $name();
        //$class = (new \Amber\Injection\Factory\InjectionFactory($class))->newInstance();      
      }
    } else {
      $class = new $class();
      $class = (new \Amber\Injection\Factory\InjectionFactory($class, 'Resolver'))->newInstance(); 
    }

    return $class->inject([]);
  }

}