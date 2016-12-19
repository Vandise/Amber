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
  private $resolverType;
  private $resolveNamespace;

  public function __construct($instance, $resolverType = null, $resolveNamespace = '\Amber\Injection\Resolve\\') {
    $this->instance = $instance;   
    $this->resolverType = $resolverType;
    $this->resolveNamespace = $resolveNamespace;
  }

  public function newInstance() : \Amber\Injection\iInjectable {
    if ($this->resolverType == null) {
      $fragments = preg_split('/(?=[A-Z])/', get_class($this->instance));
      $resolver = $this->resolveNamespace.end($fragments); unset($fragments);
    } else {
      $resolver = $this->resolveNamespace.$this->resolverType;
    }
    if (!class_exists($resolver)) {
      throw new \Amber\Injection\Exception\NoInjectionResolver(
        "Resolver for injection type '$resolver' was not found");
    }
    return new $resolver($this->instance);
  }

}