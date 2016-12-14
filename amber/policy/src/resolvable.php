<?php

namespace Amber\Policy;

abstract class Resolvable {

  protected $resolve = null;
  protected $reject = null;
  protected $resolved = false;

  public function __construct(string $resolve, string $reject) {
    $this->resolve = $resolve;
    $this->reject = $reject;
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