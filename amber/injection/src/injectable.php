<?php

namespace Amber\Injection;

/**
 * Injectable Class
 *
 * Interface enforcing standards for injectable types
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Injection
 * @since Nov 4th, 2015
 * @version v0.1
 */
abstract class Injectable implements \Amber\Injection\iInjectable {
  protected $instance;
  protected $method;
  protected $parameters = array();

  protected function className($name) : string {
    $class = '';
    $fragments = explode('_', $name);
    foreach($fragments as $f) {
      $class .= ucfirst($f);
    }
    return $class;
  }

  public function setInjectionMethod($method) {
    $this->method = $method;
    return $this;
  }

}