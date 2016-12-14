<?php

namespace Amber\Policy;

abstract class Resolvable {

  protected $resolved = null;
  protected $rejected = null;

  public function __construct(string $resolved, string $rejected) {
    $this->resolved = $resolved;
    $this->rejected = $rejected;
  }

  public function getResolveService() : string {
    return $this->resolved;
  }

  public function getRejectedResponder() : string {
    return $this->rejected;    
  }

}