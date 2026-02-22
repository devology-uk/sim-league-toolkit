<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Core\Constants;
  use SLTK\Domain\Championship;
  use SLTK\Domain\EventClass;
  use WP_REST_Request;
  use WP_REST_Response;

  class ChampionshipClassApiController extends ApiController {
    use HasDelete, HasPost;

    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP_CLASS);
    }

    public function listAvailable(WP_REST_Request $request): WP_REST_Response {

      return $this->execute(function () use ($request) {
        $id = $this->getId($request);

        $data = EventClass::listAvailableForChampionship($id);

        return ApiResponse::success(array_map(fn($i) => $i->toDto(), $data));
      });
    }

    public function list(WP_REST_Request $request): WP_REST_Response {

    return $this->execute(function () use ($request) {
      $id = $this->getId($request);

      $data = Championship::listClasses($id);

      return ApiResponse::success(array_map(fn($i) => $i->toDto(), $data));
    });
  }

    public function registerRoutes(): void {
      $this->registerRoute(ResourceNames::CHAMPIONSHIP . '/' . Constants::ROUTE_PATTERN_ID . '/classes/available', 'GET', [$this, 'canGet'], [$this, 'listAvailable']);
      $this->registerRoute(ResourceNames::CHAMPIONSHIP . '/' . Constants::ROUTE_PATTERN_ID . '/classes', 'GET', [$this, 'canGet'], [$this, 'list']);
      $this->registerRoute(ResourceNames::CHAMPIONSHIP . '/' . Constants::ROUTE_PATTERN_ID . '/classes', 'POST', [$this, 'canPost'], [$this, 'post']);
      $this->registerRoute(ResourceNames::CHAMPIONSHIP . '/' . Constants::ROUTE_PATTERN_ID . '/classes/(?P<eventClassId>\\d+)' , 'DELETE', [$this, 'canDelete'], [$this, 'delete']);
    }

    public function canGet(): bool {
      return true;
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use($request) {
        $championshipId = $this->getId($request);
        $eventClassId  = $request->get_param('eventClassId');

        Championship::deleteChampionshipClass($championshipId, $eventClassId);

        return ApiResponse::noContent();
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use($request) {
        $championshipId = $this->getId($request);
        $params = $this->getParams($request);
        $eventClassId  = $params['eventClassId'];

        Championship::addChampionshipClass($championshipId, $eventClassId);

        return ApiResponse::noContent();
      });
    }
  }