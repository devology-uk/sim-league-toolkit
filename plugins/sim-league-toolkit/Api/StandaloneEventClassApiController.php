<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Core\Constants;
  use SLTK\Domain\EventClass;
  use SLTK\Domain\StandaloneEvent;
  use WP_REST_Request;
  use WP_REST_Response;

  class StandaloneEventClassApiController extends ApiController {
    use HasDelete, HasPost;

    public function __construct() {
      parent::__construct(ResourceNames::STANDALONE_EVENT_CLASS);
    }

    public function listAvailable(WP_REST_Request $request): WP_REST_Response {

      return $this->execute(function () use ($request) {
        $id = $this->getId($request);

        $data = EventClass::listAvailableForStandaloneEvent($id);

        return ApiResponse::success(array_map(fn($i) => $i->toDto(), $data));
      });
    }

    public function list(WP_REST_Request $request): WP_REST_Response {

      return $this->execute(function () use ($request) {
        $id = $this->getId($request);

        $data = StandaloneEvent::listClasses($id);

        return ApiResponse::success(array_map(fn($i) => $i->toDto(), $data));
      });
    }

    public function registerRoutes(): void {
      $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/classes/available', 'GET', [$this, 'canGet'], [$this, 'listAvailable']);
      $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/classes', 'GET', [$this, 'canGet'], [$this, 'list']);
      $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/classes', 'POST', [$this, 'canPost'], [$this, 'post']);
      $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/classes/(?P<eventClassId>\\d+)', 'PUT', [$this, 'canPut'], [$this, 'put']);
      $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/classes/(?P<eventClassId>\\d+)', 'DELETE', [$this, 'canDelete'], [$this, 'delete']);
    }

    public function canPut(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    public function put(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $standaloneEventId = $this->getId($request);
        $eventClassId = (int)$request->get_param('eventClassId');
        $params = $this->getParams($request);

        $maxEntrants = array_key_exists('maxEntrants', $params)
          ? ($params['maxEntrants'] !== null && $params['maxEntrants'] !== '' ? (int)$params['maxEntrants'] : null)
          : null;

        StandaloneEvent::updateStandaloneEventClass($standaloneEventId, $eventClassId, ['max_entrants' => $maxEntrants]);

        return ApiResponse::noContent();
      });
    }

    public function canGet(): bool {
      return true;
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $standaloneEventId = $this->getId($request);
        $eventClassId = $request->get_param('eventClassId');

        StandaloneEvent::deleteStandaloneEventClass($standaloneEventId, $eventClassId);

        return ApiResponse::noContent();
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $standaloneEventId = $this->getId($request);
        $params = $this->getParams($request);
        $eventClassId = $params['eventClassId'];

        StandaloneEvent::addStandaloneEventClass($standaloneEventId, $eventClassId);

        return ApiResponse::noContent();
      });
    }
  }
