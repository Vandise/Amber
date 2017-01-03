<?php

namespace Amber\Injection\Resolve;

/**
 * Responder Injection
 *
 * Injects global configurations and server settings into a responder object
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Injection\Resolve
 * @since Nov 4th, 2015
 * @version v0.1
 */
class Responder extends \Amber\Injection\Injectable {

  public function __construct($instance) {
    $this->instance = $instance;
    $this->method = 'respond_to_html';
    $params = (new \ReflectionMethod($instance, 'respond_to_html'))->getParameters();
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
    $resinite = \Amber\Framework\Resinite::getInstance();
    echo "resolving $name";
    return null;
  }

}