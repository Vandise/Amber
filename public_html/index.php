<?php

  namespace Application
  {

    require_once dirname(__DIR__).'/config/constants.php';
    require_once AMBER_ROOT_PATH.'/amber/framework.php';

    session_start();

    $_SERVER['REQUEST_URI'] = (substr($_SERVER['REQUEST_URI'],-1) == '/' && strlen($_SERVER['REQUEST_URI']) > 1) ? substr($_SERVER['REQUEST_URI'],0,-1) : $_SERVER['REQUEST_URI'];

    $resinite = \Amber\Framework\Resinite::getInstance();
    $resinite->router = (new \Amber\Event\Factory\RouterFactory())->newInstance();
    $resinite->request = $_SERVER;

    require_once APP_CONFIG_PATH.'/resinite.php';

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

      $request_policies = array();
      foreach($resinite->route->params['policies'] as $policy => $reject_responder)
      {
        $route_namespace = $resinite->route->params['namespace'];
        $NAMESPACE = "\\Application\\Policies\\".($route_namespace ? ucfirst($route_namespace).'\\' : '');
        $class = $NAMESPACE.implode('', array_map(function($fragment){
          return ucfirst($fragment);
        }, explode('_', $policy))).'Policy';
        $request_policies[] = new $class($reject_responder);
      }

      foreach($request_policies as $index => $policy)
      {
        $injector = (new \Amber\Injection\Factory\InjectionFactory($policy))->newInstance();
        $status = $injector->inject([]);
        $request_policies[$index] = $injector->getInstance();
        $request_policies[$index]->setResolvedStatus($status);
      }

      $resinite->request_policies = $request_policies;

      $reject_responder_name = false;
      foreach($resinite->request_policies as $policy)
      {
        if (!$policy->getResolvedStatus())
        {
          $reject_responder_name = $policy->getRejectResponder();
          break;
        }
      }
      if ($reject_responder_name)
      {
        $reject_responder_name = '\Application\Responders\\'.ucfirst($reject_responder_name).'Responder';
        $responder = new $reject_responder_name();
        $method_name = 'respond_to_'.($resinite->route->params['format'] ? $resinite->route->params['format'] : 'html');

        $parameters = array(
          'policy_results' => $resinite->request_policies,
          'service' => null,
          'renderer' => null, /* resinite->renderer */
          );
        $injector = (new \Amber\Injection\Factory\InjectionFactory($responder))->newInstance();
        $response = $injector
                      ->setInjectionMethod($method_name)
                      ->inject($parameters);
        
        call_user_func_array(array($responder, $method_name), $parameters);
      }
      else
      {
        // TODO: anything beginning with \ is a custom path
        echo "<pre>";

        $route_namespace = $resinite->route->params['namespace'];
        $NAMESPACE = "\\Application\\Services\\".($route_namespace ? ucfirst($route_namespace).'\\' : '');
        $class = $NAMESPACE.implode('', array_map(function($fragment){
          return ucfirst($fragment);
        }, explode('_',
          ( $resinite->route->params['service'] ? $resinite->route->params['service'] : $resinite->route->params['action'])
        ))).'Service';

        // TODO: construct base service with parameters for $this->something instead of injection
        $parameters = array(
          'params' => $resinite->route->params,
        );
        foreach($resinite->request_policies as $policy)
        {
          $parameters = array_merge($parameters, $policy->getInjectedParameters());
        }
        $service = new $class();

        call_user_func_array(array($service, 'execute'), $parameters);
        
        echo "Execute service \n";
      }
    }
    else
    {
      echo "Could not match route: ".$resinite->request_path;
    }
  }