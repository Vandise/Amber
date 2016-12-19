<?php

namespace Amber\Injection\Factory;

/**
 * Injection Factory
 *
 * Constructs dependency injection resolvers
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Injection\Factory
 * @since Nov 4th, 2015
 * @version v0.1
 */
 class InjectionFactory {

  private $instance;
  private $resolveNamespace;

  public function __construct($instance, $resolveNamespace = '\Amber\Injection\Resolve\\') {
    $this->instance = $instance;   
    $this->resolveNamespace = $resolveNamespace;
  }

  public function newInstance() : \Amber\Injection\iInjectable {
    $fragments = preg_split('/(?=[A-Z])/', get_class($this->instance));
    $resolver = $this->resolveNamespace.end($fragments); unset($fragments);
    if (!class_exists($resolver)) {
      throw new \Amber\Injection\Exception\NoInjectionResolver(
        "Resolver for injection type '$resolver' was not found");
    }
    return new $resolver($this->instance);
  }

}