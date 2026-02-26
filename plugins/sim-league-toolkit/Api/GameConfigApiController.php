<?php

  namespace SLTK\Api;

  use InvalidArgumentException;
  use SLTK\Config\GameConfigProvider;
  use WP_REST_Request;
  use WP_REST_Response;

  class GameConfigApiController extends ApiController {

    public function __construct() {
      parent::__construct(ResourceNames::GAME_CONFIG);
    }

    public function registerRoutes(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute($resourceName, 'GET', [$this, 'canGetAll'], [$this, 'getAll']);
      $this->registerRoute($resourceName . '/(?P<gameId>[a-zA-Z0-9_-]+)', 'GET', [$this, 'canGetByGameId'], [$this, 'getByGameId']);
    }

    public function canGetAll(): bool {
      return true;
    }

    public function canGetByGameId(): bool {
      return true;
    }

    public function getAll(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () {
        $games = GameConfigProvider::getAvailableGames();

        return ApiResponse::success($games);
      });
    }

    public function getByGameId(WP_REST_Request $request): WP_REST_Response {
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
