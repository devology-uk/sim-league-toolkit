<?php

  namespace SLTK\Api;

  use SLTK\Domain\Server;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class ServerApiController extends BasicApiController {

    public function __construct() {
      parent::__construct(ResourceNames::SERVER);
    }

    public function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = Server::list();

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onRegisterRoutes(): void {

    }
  }