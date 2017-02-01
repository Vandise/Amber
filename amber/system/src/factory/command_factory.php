<?php

  namespace Amber\System\Factory;

  class CommandFactory {
    public static function create($cmd, $opts) {
      $class = "\\Amber\\System\\Command\\".$cmd;
      return new $class($opts);
    }
  }