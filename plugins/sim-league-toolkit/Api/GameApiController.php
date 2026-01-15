<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Domain\Car;
  use SLTK\Domain\EventClass;
  use SLTK\Domain\Game;
  use SLTK\Domain\Platform;
  use SLTK\Domain\Track;
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
    public function listCarClasses(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Car::listClassesForGame($id);

      return rest_ensure_response($data);
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
    public function listEventClasses(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = EventClass::listForGame($id);  $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
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

    /**
     * @throws Exception
     */
    public function listTrackLayouts(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Track::listLayoutsForTrack($id);

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    /**
     * @throws Exception
     */
    public function listTracks(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Track::listForGame($id);

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
      $this->registerEventClassesRoute();
      $this->registerPlatformsRoute();
      $this->registerTracksRoute();
      $this->registerTrackLayoutsRoute();
    }

    private function registerCarClassesRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>\d+)/car-classes';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'listCarClasses');
    }

    private function registerCarsRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>[\d]+)/cars/(?P<class>[\w]+)';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'listCars');
    }

    private function registerPlatformsRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>\d+)/platforms';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'listPlatforms');
    }

    private function registerTrackLayoutsRoute(): void {
      $route = $this->getResourceName() . '/tracks/(?P<id>\d+)/layouts';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'listTrackLayouts');
    }

    private function registerTracksRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>\d+)/tracks';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'listTracks');
    }

    private function registerEventClassesRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>\d+)/event-classes';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'listEventClasses');
    }
  }