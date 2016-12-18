<?php

namespace Amber\Policy\Test;

class BrokenPolicy extends \Amber\Policy\Resolvable {
  public function resolves() : bool {
    return false;
  }
}

class LoginPolicy extends \Amber\Policy\Resolvable {
  public function resolve($current_user) : bool {
    return true;
  }
}

class ResolvableTest extends \PHPUnit_Framework_TestCase {

  protected function setUp() {
    parent::setUp();
  }

  /**
   * @expectedException \Amber\Policy\Exception\NoResolveMethod
   */ 
  public function testConstructThrowsExceptionWhenNoResolveMethodIsPresent() {
    $broken = new BrokenPolicy('login_success', 'unauthorized');
  }

  public function testSetResolvedStatus() {
    $policy = new LoginPolicy('login_success', 'unauthorized');
    $policy->setResolvedStatus(true);
    $this->assertEquals($policy->getResolvedStatus(),
      true);
  }

  public function testGetResolveService() {
    $policy = new LoginPolicy('login_success', 'unauthorized');
    $this->assertEquals($policy->getResolveService(),
      'login_success');
  }
}