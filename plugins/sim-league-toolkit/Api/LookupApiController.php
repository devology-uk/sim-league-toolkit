<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  abstract class LookupApiController extends ApiController {

    public function __construct(string $resourceName) {
      parent::__construct($resourceName);
    }

    /**
     * @throws Exception
     */
    public function get(WP_REST_Request $request): WP_REST_Response {
      return $this->onGet($request);
    }

    /**
     * @throws Exception
     */
    public function getById(WP_REST_Request $request): WP_REST_Response {
      return $this->onGetById($request);
    }


    public function registerRoutes(): void {
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->onRegisterRoutes();
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }


    protected abstract function onGet(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onGetById(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onRegisterRoutes(): void;

    private function registerGetByIdRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>\d+)';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'getById');
    }

    private function registerGetRoute(): void {
      $route = $this->getResourceName() . 's';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'get');
    }
  }