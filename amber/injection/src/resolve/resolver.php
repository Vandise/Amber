<?php

namespace Amber\Injection\Resolve;

/**
 * Resolver Injection
 *
 * Injects global configurations and server settings into a resolver object
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Injection\Resolve
 * @since Nov 4th, 2015
 * @version v0.1
 */
class Resolver extends \Amber\Injection\Injectable {

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
    return 'server settings, configs, etc';
  }

}