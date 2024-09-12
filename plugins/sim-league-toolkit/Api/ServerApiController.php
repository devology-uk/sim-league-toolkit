<?php

  namespace SLTK\Api;

  use SLTK\Domain\Server;
  use WP_REST_Request;
  use WP_REST_Response;

  /**
   * Custom api handler for servers
   */
  class ServerApiController extends ApiController {
    private const string RESOURCE_BASE = '/server';

    /**
     * @param WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function index(WP_REST_Request $request): WP_REST_Response {
      $servers = Server::list();

      $responseData = array_map(function($server) {
        return $server->toDto();
      }, $servers);

      return rest_ensure_response($responseData);
    }

    /**
     * @inheritDoc
     */
    protected function canExecute(): bool {
      return current_user_can('manage_options');
    }

    /**
     * @inheritDoc
     */
    public function registerRoutes(): void {
      register_rest_route(self::NAMESPACE,
                          self::RESOURCE_BASE,
                          [
                            [
                              'methods'             => 'GET',
                              'callback'            => [$this, 'index'],
                              'permission_callback' => [$this, 'checkPermission'],
                            ]
                          ]);
    }
  }