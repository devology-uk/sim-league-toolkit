<?php

  namespace SLTK\Api;

  use SLTK\Domain\Server;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class ServerApiController extends ApiController {

    public function __construct() {
      parent::__construct(ResourceNames::SERVER);
    }

    public function get(WP_REST_Request $request): WP_REST_Response {
      $servers = Server::list();

      $responseData = array_map(function ($server) {
        return $server->toDto();
      }, $servers);

      return rest_ensure_response($responseData);
    }

    public function registerRoutes(): void {
      $this->registerGetRoute();
    }

    protected function canExecute(): bool {
      return current_user_can('manage_options');
    }

    private function registerGetRoute(): void {
      register_rest_route(self::NAMESPACE,
        self::getResourceName(),
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]);
    }
  }