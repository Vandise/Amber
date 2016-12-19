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
  }

  public function getInstance() {
    
  }

  private function resolveDepenency($name) {
    
  }

}