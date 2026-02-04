<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Domain\Car;
  use SLTK\Domain\EventClass;
  use SLTK\Domain\Game;
  use SLTK\Domain\Platform;
  use SLTK\Domain\Track;
  use WP_REST_Request;
  use WP_REST_Response;

  class GameApiController extends ApiController {
    use HasGet, HasGetById;

    public function __construct() {
      parent::__construct(ResourceNames::GAME);
    }

    public function listCarClasses(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Car::listClassesForGame($this->getId($request));

        return ApiResponse::success($data);
      });
    }

    public function listCars(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Car::listForGame((int)$request->get_param('gameId'), $request->get_param('carClass'));

        return ApiResponse::success($data);
      });
    }

    public function listEventClasses(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = EventClass::listForGame($this->getId($request));

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    public function listPlatforms(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Game::getPlatforms($this->getId($request));

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    public function listTrackLayouts(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Game::getTrackLayouts($this->getId($request));

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    public function listTracks(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Game::getTracks($this->getId($request));

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    public function registerRoutes(): void {
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $routeBase = $this->getResourceName() . '/(?P<id>\\d+)/';
      $this->registerRoute($routeBase . 'cars', 'GET', [$this, 'canRead'], [$this, 'listCars']);
      $this->registerRoute($routeBase . 'car-classes', 'GET', [$this, 'canRead'], [$this, 'listCarClasses']);
      $this->registerRoute($routeBase . 'event-classes', 'GET', [$this, 'canRead'], [$this, 'listEventClasses']);
      $this->registerRoute($routeBase . 'platforms', 'GET', [$this, 'canRead'], [$this, 'listPlatforms']);
      $this->registerRoute($routeBase . 'tracks', 'GET', [$this, 'canRead'], [$this, 'listTracks']);
      $this->registerRoute($this->getResourceName() . '/tracks/(?P<id>\\d+)', 'GET', [$this, 'canRead'], [$this, 'listTrackLayouts']);
    }


    public function canRead(): bool {
      return true;
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Game::list();

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Game::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('Game');
        }

        return ApiResponse::success($data->toDto());
      });
    }
  }