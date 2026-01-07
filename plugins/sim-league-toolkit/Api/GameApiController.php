<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Domain\Car;
  use SLTK\Domain\Game;
  use SLTK\Domain\Platform;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class GameApiController extends LookupApiController {

    public function __construct() {
      parent::__construct(ResourceNames::GAME);
    }

    /**
     * @throws Exception
     */
    public function listCars(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');
      $class = $request->get_param('class');

      $data = Car::listForGame($id, $class);

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = $item->toDto();
      }

      return rest_ensure_response($responseData);
    }

    /**
   * @throws Exception
   */
    public function listCarClasses(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Car::listClassesForGame($id);

      return rest_ensure_response($data);
    }

    /**
     * @throws Exception
     */
    public function listPlatforms(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Platform::listForGame($id);
      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    public function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = Game::list();

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

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
      $this->registerCarsRoute();
      $this->registerCarClassesRoute();
      $this->registerPlatformsRoute();
    }

    private function registerCarClassesRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>\d+)/car-classes',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'listCarClasses'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerCarsRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>[\d]+)/cars/(?P<class>[\w]+)',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'listCars'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerPlatformsRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>\d+)/platforms',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'listPlatforms'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }
  }