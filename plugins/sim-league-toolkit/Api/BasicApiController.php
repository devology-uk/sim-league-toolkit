<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  abstract class BasicApiController extends ApiController {

    private string $resourceName;

    public function __construct(string $resourceName) {
      $this->resourceName = $resourceName;
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
    public function get(WP_REST_Request $request): WP_REST_Response {
      return $this->onGet($request);
    }

    /**
     * @throws Exception
     */
    public function getById(WP_REST_Request $request): WP_REST_Response {
      return $this->onGetById($request);
    }

    /**
     * @throws Exception
     */
    public function post(WP_REST_Request $request): WP_REST_Response {
      return $this->onPost($request);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function getResourceName(): string {
      return $this->resourceName;
    }

    protected abstract function onDelete(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onGet(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onGetById(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onPost(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onRegisterRoutes(): void;

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
        $this->getResourceName() . '/(?P<id>\d+)',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get'],
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