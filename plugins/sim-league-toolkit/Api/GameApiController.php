<?php

  namespace SLTK\Api;

  use SLTK\Core\Constants;
  use SLTK\Domain\Game;
  use WP_REST_Request;
  use WP_REST_Response;

  class GameApiController extends LookupApiController {

    public function __construct() {
      parent::__construct(ResourceNames::GAME);
    }

    public function onGet(WP_REST_Request $request): WP_REST_Response {
      $games = Game::list();

      if (empty($games)) {
        return rest_ensure_response($games);
      }

      $responseData = [];

      foreach ($games as $game) {
        $responseData[] = $game->toDto();
      }

      return rest_ensure_response($responseData);
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Game::get($id);

      return rest_ensure_response($data->toDto());
    }

    protected function onRegisterRoutes(): void {

    }
  }