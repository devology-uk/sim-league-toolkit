<?php

  namespace SLTK\Api;

  use Exception;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  abstract class BasicApiController extends LookupApiController {

    public function __construct(string $resourceName) {
      parent::__construct($resourceName);
    }

    /**
     * @throws Exception
     */
    public function delete(WP_REST_Request $request): WP_REST_Response {
      return $this->onDelete($request);
    }

    /**
     * @throws Exception
     */
    public function post(WP_REST_Request $request): WP_REST_Response {
      return $this->onPost($request);
    }

    public function registerRoutes(): void {
      parent::registerRoutes();
      $this->registerDeleteRoute();
      $this->registerPostRoute();
    }

    protected abstract function onDelete(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onPost(WP_REST_Request $request): WP_REST_Response;

    private function registerDeleteRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>\d+)',
        [
          [
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => [$this, 'delete'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerPostRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName(),
        [
          [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'post'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }
  }