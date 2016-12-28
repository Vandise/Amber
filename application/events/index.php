<?php

  $resinite->router->setNamespace('','/',function($router){

  	$router->add('index','')->addValues(
  		array(
  			'service'=>'index',
  			'format'=>'html'
  		)
  	);

  });