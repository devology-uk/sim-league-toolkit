<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Domain\Game;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class GamesApiController extends ApiController {
    private const string RESOURCE_BASE = '/game';

    /**
     * @throws Exception
     */
    public function list(WP_REST_Request $request): WP_REST_Response {
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
      $this->registerListRoute();
    }

    private function getSchema(): array {
      return [
        '$schema' => 'http://json-schema.org/draft-04/schema#',
        'title' => 'game',
        'type' => 'object',
        'properties' => [
          'id' => [
            'description' => esc_html__('The id the game.', 'racket-club'),
            'type' => 'integer',
            'context' => ['view'],
            'readonly' => true,
          ],
          'name' => [
            'description' => esc_html__('The name of the game.', 'racket-club'),
            'type' => 'string',
            'context' => ['view'],
            'readonly' => true,
          ],
          'latestVersion' => [
            'description' => esc_html__('The latest version of the game that is supported.', 'racket-club'),
            'type' => 'string',
            'context' => ['view'],
            'readonly' => true
          ],
          'supportsResultUpload' => [
            'description' => esc_html__('Indicates whether uploading of result files produced by the game is supported.', 'racket-club'),
            'type' => 'boolean',
            'context' => ['view'],
            'readonly' => true
          ],
          'published' => [
            'description' => esc_html__('Indicates whether support for the game has been published or is in development.', 'racket-club'),
            'type' => 'boolean',
            'context' => ['view'],
            'readonly' => true
          ],
          'supportsLayouts' => [
            'description' => esc_html__('Indicates whether the game supports multiple layouts for tracks.', 'racket-club'),
            'type' => 'boolean',
            'context' => ['view'],
            'readonly' => true
          ]
        ]
      ];
    }

    private function registerListRoute(): void {
      register_rest_route(self::NAMESPACE,
        self::RESOURCE_BASE,
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'list'],
            'permission_callback' => [$this, 'checkPermission'],
          ],
          'schema' => $this->getSchema()
        ]
      );
    }

    protected function canExecute(): bool {
      return current_user_can('read');
    }
  }