<?php

  $resinite->router->setNamespace('','/',function($router){

    $router->addValues(array(
      'policies' => [
          'requires_login' => ['login_success', 'unauthorized']
        ]
    ));

  	$router->add('index','')->addValues(
  		array(
  			'service'=>'index',
  			'format'=>'html'
  		)
  	);

  });