<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Constants;
  use SLTK\Domain\EventSession;
  use WP_REST_Request;
  use WP_REST_Response;

  class EventSessionApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::EVENT_SESSION);
    }

    public function listByEventRef(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $eventRefId = (int)$request['eventRefId'];
        $data = EventSession::listByEventRefId($eventRefId);

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    public function registerRoutes(): void {
      $resource = $this->getResourceName();

      $this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
      $this->registerPutRoute();
      $this->registerRoute('/event-refs/(?P<eventRefId>\d+)/eventSessions', 'GET', [$this, 'canListByEventReg'], [$this, 'listByEventRef']);
      $this->registerRoute('/event-refs/(?P<eventRefId>\d+)/eventSessions/reorder', 'POST', [$this, 'canReorder'], [$this, 'reorder']);
    }

    public function reorder(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $eventRefId = (int)$request['eventRefId'];
        $eventSessionIds = $this->getParams($request)['eventSessionIds'] ?? [];

        EventSession::reorder($eventRefId, $eventSessionIds);

        return ApiResponse::noContent();
      });
    }

    protected function onDelete(WP_REST_Request $request): void {
      $this->execute(function () use ($request) {
        EventSession::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () {
        $data = EventSession::list();

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = EventSession::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('EventSession');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = $this->hydrateFromRequest(new EventSession(), $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Event Session', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = EventSession::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('EventSession');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Event Session', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function canListByEventReg(): bool {
      return true;
    }

    private function canReorder(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    private function hydrateFromRequest(EventSession $session, WP_REST_Request $request): EventSession {
      $params = $this->getParams($request);

      $session->setEventRefId((int)$params['eventRefId']);
      $session->setName(sanitize_text_field($params['name'] ?? ''));
      $session->setGameId(sanitize_text_field($params['gameId'] ?? ''));
      $session->setSessionType(sanitize_text_field($params['sessionType'] ?? ''));
      $session->setStartTime(sanitize_text_field($params['startTime'] ?? '08:00'));
      $session->setDuration((int)($params['duration'] ?? 15));
      $session->setSortOrder((int)($params['sortOrder'] ?? 0));
      $session->setAttributes($params['attributes'] ?? []);

      return $session;
    }
  }