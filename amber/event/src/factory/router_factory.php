<?php

namespace Amber\Event\Factory;

/**
 * Router Factory
 *
 * The default router constructor
 *
 * @author Benjamin J. Anderson <andeb2804@gmail.com>
 * @package Amber\Event\Factory
 * @since Nov 4th, 2015
 * @version v0.1.0
 */
class RouterFactory
{
  public function newInstance()
  {
    $router = new \Amber\Event\Dispatch\Router(
      new \Amber\Event\Dispatch\RouteCollection(
          new \Amber\Event\Factory\RouteFactory()
        ),
        new \Amber\Event\Dispatch\RouteGenerator());
    return $router;
  }

}