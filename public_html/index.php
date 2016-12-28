<?php

  namespace Application
  {

    require_once dirname(__DIR__).'/config/constants.php';
    require_once AMBER_ROOT_PATH.'/amber/framework.php';

    $_SERVER['REQUEST_URI'] = (substr($_SERVER['REQUEST_URI'],-1) == '/' && strlen($_SERVER['REQUEST_URI']) > 1) ? substr($_SERVER['REQUEST_URI'],0,-1) : $_SERVER['REQUEST_URI'];

    $resinite = \Amber\Framework\Resinite::getInstance();
    $resinite->router = (new \Amber\Event\Factory\RouterFactory())->newInstance();
    $resinite->request = $_SERVER;

    foreach (glob(APP_ROOT_PATH."/events/*.php") as $file_name)
    {
      require_once $file_name;
    }

    $path = parse_url($resinite->request["REQUEST_URI"], PHP_URL_PATH);
    $resinite->request_path = $path;
    $resinite->route = $resinite->router->match(
                                $resinite->request_path,
                                  $resinite->request);

    if ($resinite->route)
    {
      echo "<pre>";
      var_dump($resinite);     
    }
    else
    {
      echo "Could not match route: ".$resinite->request_path;
    }
  }