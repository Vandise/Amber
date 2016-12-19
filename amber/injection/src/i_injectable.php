<?php

namespace Amber\Injection;

/**
 * Injectable Interface
 *
 * Interface enforcing standards for injectable types
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Injection
 * @since Nov 4th, 2015
 * @version v0.1
 */
interface iInjectable {
  public function __construct($instance);
  public function inject(array $overrides);
  public function getInstance();
}