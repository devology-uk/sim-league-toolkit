<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Domain\Game;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class GameApiController extends ApiController {
    private const string RESOURCE_BASE = '/' . ResourceNames::GAME;

    /**
     * @throws Exception
     */
    public function get(WP_REST_Request $request): WP_REST_Response {
      $games = Game::list();

      if (empty($games)) {
        return rest_ensure_response($games);
      }

      $responseData = [];

      foreach ($games as $game) {
        $responseData[] = $game->toTableItem();
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
        self::RESOURCE_BASE,
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