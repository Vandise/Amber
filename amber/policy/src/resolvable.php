<?php

namespace Amber\Policy;

/**
 * Resolvable
 *
 * Base policy object
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Policy
 * @since Nov 4th, 2015
 * @version v0.1
 */
abstract class Resolvable {

  protected $resolve = null;
  protected $reject = null;
  protected $resolved = false;

  public function __construct(string $resolve, string $reject) {
    $this->resolve = $resolve;
    $this->reject = $reject;
    if(!(method_exists($this, 'resolve'))) {
      throw new \Amber\Policy\Exception\NoResolveMethod('Class '.get_class($this).' must implement a "resolve" method.');
    }
  }

  public function setResolvedStatus(bool $status) : void {
    $this->resolved = $status;
  }

  public function getResolvedStatus() : bool {
    return $this->resolved;
  }

  public function getResolveService() : string {
    return $this->resolve;
  }

  public function getRejectResponder() : string {
    return $this->reject;    
  }

}