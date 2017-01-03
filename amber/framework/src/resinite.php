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
        static::$instance->resolvers = array();
        static::$instance->implementations = array();
        static::$instance->values = array();
      }
      return static::$instance;
    }

    public function addResolver($name, $resolver)
    {
      static::$instance->data['resolvers'][$name] = $resolver;
    }

    public function addImplementation($name, $implementation)
    {
      static::$instance->data['implementations'][$name] = $implementation;
    }

    public function addValue($name, $value)
    {
      static::$instance->data['values'][$name] = $value;
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