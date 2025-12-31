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
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>\d+)',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'getById'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerGetRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName(),
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }
  }