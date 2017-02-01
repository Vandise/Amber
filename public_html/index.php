<?php

  namespace Application
  {

    /*

        Initialize Amber Framework
          - constants
          - amber root framework initializer
          - session_start

    */
    require_once dirname(__DIR__).'/config/constants.php';
    require_once AMBER_ROOT_PATH.'/amber/framework.php';

    session_start();


    /*

        Configure Resinite and Router
          - format URI if no trailing /
          - register the router to resinite
          - add the global request to resinite
          - initialize any user-defined values in /config/resinite
    */
    $_SERVER['REQUEST_URI'] = (substr($_SERVER['REQUEST_URI'],-1) == '/' && strlen($_SERVER['REQUEST_URI']) > 1) ? substr($_SERVER['REQUEST_URI'],0,-1) : $_SERVER['REQUEST_URI'];

    $resinite = \Amber\Framework\Resinite::getInstance();
    $resinite->router = (new \Amber\Event\Factory\RouterFactory())->newInstance();
    $resinite->request = $_SERVER;

    require_once APP_CONFIG_PATH.'/resinite.php';

    /*

        Load routes and attemt to find a match
        - all route events in application/events
        - match the formatted request uri to the defined routes

    */
    foreach (glob(APP_ROOT_PATH."/events/*.php") as $file_name)
    {
      require_once $file_name;
    }

    $path = parse_url($resinite->request["REQUEST_URI"], PHP_URL_PATH);
    $resinite->request_path = $path;
    $resinite->route = $resinite->router->match(
                                $resinite->request_path,
                                  $resinite->request);

    /*

        Router has found a match
          - load all policies associated with the route
          - inject any dependencies to the policies <resolvable> should handle this
            overrides currently does nothing
          - execute the policy and set the resolved status
          - add policies to resinite
          - check if policies were rejected
          - execute appropriate service or responder

    */
    if ($resinite->route)
    {

      /*

          Construct / load all policy objects

      */

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


      /*

          Execute the policy, set a resolved status

      */
      foreach($request_policies as $index => $policy)
      {
        $injector = (new \Amber\Injection\Factory\InjectionFactory($policy))->newInstance();
        $status = $injector->inject([]);
        $request_policies[$index] = $injector->getInstance();
        $request_policies[$index]->setResolvedStatus($status);
      }

      /*

          Add policies to resinite

      */
      $resinite->request_policies = $request_policies;


      /*

          Check if any of the policies were rejected

      */
      $reject_responder_name = false;
      foreach($resinite->request_policies as $policy)
      {
        if (!$policy->getResolvedStatus())
        {
          $reject_responder_name = $policy->getRejectResponder();
          break;
        }
      }

      $route_namespace = $resinite->route->params['namespace'];


      /*

          A policy was rejected, load appropriate repsonder

      */
      if ($reject_responder_name)
      {
        $reject_responder_name =
          '\Application\Responders\\'
          .($route_namespace ? ucfirst($route_namespace).'\\' : '')
          .ucfirst($reject_responder_name).'Responder';

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

      /*

          Policies accepted. Execute service and responder.

      */
      else
      {

        /*
            TODO: anything beginning with \ is a custom path
    
            Execute the service

        */
        echo "<pre>";

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

        echo "Execute service \n";
        call_user_func_array(array($service, 'execute'), $parameters);



        /*

            Execute the responder

        */
        $NAMESPACE = "\\Application\\Responders\\".($route_namespace ? ucfirst($route_namespace).'\\' : '');
        $class = $NAMESPACE.implode('', array_map(function($fragment){
          return ucfirst($fragment);
        }, explode('_',
          ( $resinite->route->params['service'] ? $resinite->route->params['service'] : $resinite->route->params['action'])
        ))).'Responder';

        var_dump($class);

      }
    }
    else
    {
      echo "Could not match route: ".$resinite->request_path;
    }
  }