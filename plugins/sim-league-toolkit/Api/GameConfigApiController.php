<?php

  namespace SLTK\Api;

  use SLTK\Config\GameConfigProvider;
  use WP_REST_Request;
  use WP_REST_Response;

  class GameConfigApiController extends ApiController
  {
    public function __construct()
    {
      parent::__construct(ResourceNames::GAME_CONFIG);
    }

    public function registerRoutes(): void
    {
      $resource = $this->getResourceName();

      $this->registerRoute($resource, 'GET', 'list');
      $this->registerRoute($resource . '/(?P<gameId>[a-zA-Z0-9_-]+)', 'GET', 'get');
    }

    protected function canExecute(): bool
    {
      return current_user_can('manage_options');
    }

    public function list(): WP_REST_Response
    {
      return $this->execute(function()
      {
        $games = GameConfigProvider::getAvailableGames();
        return ApiResponse::success($games);
      });
    }

    public function get(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        $gameId = sanitize_text_field($request['gameId']);

        try
        {
          $config = GameConfigProvider::load($gameId);
          return ApiResponse::success($config);
        }
        catch (\InvalidArgumentException $e)
        {
          return ApiResponse::notFound('Game configuration');
        }
      });
    }
  }