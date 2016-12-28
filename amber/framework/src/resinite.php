<?php

  namespace Amber\Framework;

  class Resinite
  {
    protected static $instance = null;
    protected $data = array();

    private function __construct(){}

    public static function getInstance()
    {
      if (!isset(static::$instance))
      {
        static::$instance = new static;
      }
      return static::$instance;
    }

    public function __set($name, $value)
    {
      static::$instance->data[$name] = $value;
    }

    public function __get($name)
    {
      return static::$instance->data[$name];
    }
  }