<?php

  namespace Amber\System\Command;

  use \GetOptionKit\OptionCollection;
  use \GetOptionKit\OptionParser;
  use \GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

  class Base {

    protected $received_options = array();
    protected $parser = null;
    protected $option_printer = null;
    protected $options = null;

    public function __construct($options) {
      $this->received_options = $options;
      $this->options = new OptionCollection();
      $this->option_printer = new ConsoleOptionPrinter();
      $this->setOptions();
      $this->parser = new OptionParser($this->options);
    }

    protected function setOptions() {
      
    }

    protected function defaultAction() {
      echo $this->option_printer->render($this->options);
    }

    public function execute() {
      $this->defaultAction();
    }

  }