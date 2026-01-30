<?php

  namespace SLTK\Api;

  use InvalidArgumentException;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Config\GameConfigProvider;
  use WP_REST_Request;
  use WP_REST_Response;

  class GameConfigApiController extends ApiController {
    use HasGet, HasGetById;

    public function __construct() {
      parent::__construct(ResourceNames::GAME_CONFIG);
    }

    public function registerRoutes(): void {
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () {
        $games = GameConfigProvider::getAvailableGames();

        return ApiResponse::success($games);
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $gameId = sanitize_text_field($request['gameId']);

        try {
          $config = GameConfigProvider::load($gameId);

          return ApiResponse::success($config);
        } catch (InvalidArgumentException $e) {
          return ApiResponse::notFound('Game configuration');
        }
      });
    }
  }