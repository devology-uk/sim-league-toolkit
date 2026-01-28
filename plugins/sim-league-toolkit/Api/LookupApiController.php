<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use WP_REST_Request;
  use WP_REST_Response;

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
      $resourceName = $this->getResourceName();
      $this->registerGetRoute("$resourceName/(?P<id>\\d+)", 'getById');
      $this->registerGetRoute("{$resourceName}s", 'get');
      $this->onRegisterRoutes();
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected abstract function onGet(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onGetById(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onRegisterRoutes(): void;
  }