<?php

  $args = $argv;
  $app = $args[1];
  array_splice($args, 1, 1);
  
  try {
    $cmd = \Amber\System\Factory\CommandFactory::create($app, $args);
    $cmd->execute();
  } catch (Exception $e) {
    echo $e->getMessage()."\n\n";
  }
