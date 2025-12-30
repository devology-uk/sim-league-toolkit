<?php

  namespace SLTK\Api;

  use SLTK\Core\Constants;
  use SLTK\Domain\RaceNumber;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class RaceNumberApiController extends ApiController {

    public function __construct() {
      parent::__construct(ResourceNames::RACE_NUMBER);
    }

    public function get(WP_REST_Request $request): WP_REST_Response {
      $data = RaceNumber::list();

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = rest_ensure_response($item->toDto());
      }

      return rest_ensure_response($responseData);
    }

    public function registerRoutes(): void {
      $this->registerGetRoute();
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
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
        ]
      );
    }
  }