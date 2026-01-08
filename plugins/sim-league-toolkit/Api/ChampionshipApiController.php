<?php

  namespace SLTK\Api;

  use SLTK\Api\BasicApiController;
  use WP_REST_Request;
  use WP_REST_Response;

  class ChampionshipApiController extends BasicApiController {
    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP);
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onRegisterRoutes(): void {

    }
  }