<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Constants;
  use SLTK\Domain\ChampionshipEvent;
  use SLTK\Domain\EventSession;
  use WP_REST_Request;
  use WP_REST_Response;

  class EventSessionApiController extends ApiController {
    use HasDelete, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::EVENT_SESSION);
    }

    public function listByEventRef(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $eventRefId = (int)$request['eventRefId'];
        $data = ChampionshipEvent::listSessions($eventRefId);

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
      $this->registerPutRoute();
      $this->registerRoute('/event-refs/(?P<eventRefId>\d+)/event-sessions', 'GET', [$this, 'canListByEventRef'], [$this, 'listByEventRef']);
      $this->registerRoute('/event-refs/(?P<eventRefId>\d+)/event-sessions/reorder', 'POST', [$this, 'canReorder'], [$this, 'reorder']);
    }

    public function reorder(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $eventRefId = (int)$request['eventRefId'];
        $sessionIds = $this->getParams($request)['sessionIds'] ?? [];

        ChampionshipEvent::reorderSessions($eventRefId, $sessionIds);

        return ApiResponse::noContent();
      });
    }

    public function canListByEventRef(): bool {
      return true;
    }

    public function canReorder(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        ChampionshipEvent::deleteSession($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = ChampionshipEvent::getSessionById($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('EventSession');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = $this->hydrateFromRequest(new EventSession(), $request);

        if (!ChampionshipEvent::saveSession($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to save Event Session', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = ChampionshipEvent::getSessionById($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('EventSession');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!ChampionshipEvent::saveSession($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to update Event Session', 'sim-league-toolkit'));
        }

        return ApiResponse::noContent();
      });
    }

    private function hydrateFromRequest(EventSession $session, WP_REST_Request $request): EventSession {
      $params = $this->getParams($request);

      $session->setEventRefId((int)$params['eventRefId']);
      $session->setName(sanitize_text_field($params['name'] ?? ''));
      $session->setGameId(sanitize_text_field($params['gameId'] ?? ''));
      $session->setSessionType(sanitize_text_field($params['sessionType'] ?? ''));
      $session->setSortOrder((int)($params['sortOrder'] ?? 0));
      $session->setAttributes($params['attributes'] ?? []);

      return $session;
    }
  }
