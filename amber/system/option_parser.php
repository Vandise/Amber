<?php

  use GetOptionKit\OptionCollection;
  use GetOptionKit\OptionParser;
  use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

  $specs = new OptionCollection();

  //  php -S localhost:8080 -t public_html/ amber/util/dev_server.php
  $specs->add('s|server:', "host:port")
    ->isa('String')
    ->defaultValue('127.0.0.1:8080');

  $printer = new ConsoleOptionPrinter();
  echo $printer->render($specs);      