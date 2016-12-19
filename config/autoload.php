<?php

function application_load($class) {
  $class = strtolower(preg_replace('/\B([A-Z])/', '_$1', $class));
  $fragments = explode( '\\', $class );
  $file = APP_ROOT_PATH.
    implode( '', 
      array_map( 
        function($fragment, $i) use ($class){
          return "/$fragment"; 
        }, $fragments, array_keys($fragments)
      )
    ).'.php';
    if(file_exists($file))
    {
      require_once $file;
      return $file;
    }
    return $file;
}

spl_autoload_register('application_load');