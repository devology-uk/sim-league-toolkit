<?php

  namespace SLTK\Api;

  use SLTK\Domain\EventSession;
  use WP_REST_Request;
  use WP_REST_Response;

  class EventSessionApiController extends ApiController
  {
    public function __construct()
    {
      parent::__construct(ResourceNames::EVENT_SESSION);
    }

    public function registerRoutes(): void
    {
      $resource = $this->getResourceName();

      $this->registerRoute($resource, 'GET', 'list');
      $this->registerRoute($resource, 'POST', 'add');
      $this->registerRoute($resource . '/(?P<id>\d+)', 'GET', 'get');
      $this->registerRoute($resource . '/(?P<id>\d+)', 'PUT', 'update');
      $this->registerRoute($resource . '/(?P<id>\d+)', 'DELETE', 'delete');
      $this->registerRoute('/event-refs/(?P<eventRefId>\d+)/eventSessions', 'GET', 'listByEventRef');
      $this->registerRoute('/event-refs/(?P<eventRefId>\d+)/eventSessions/reorder', 'PUT', 'reorder');
    }

    protected function canExecute(): bool
    {
      return current_user_can('manage_options');
    }

    public function list(): WP_REST_Response
    {
      return $this->execute(function()
      {
        $eventSessions = EventSession::list();

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $eventSessions)
        );
      });
    }

    public function listByEventRef(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        $eventRefId = (int)$request['eventRefId'];
        $eventSessions = EventSession::listByEventRefId($eventRefId);

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $eventSessions)
        );
      });
    }

    public function get(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        $eventSession = EventSession::get($this->getId($request));

        if ($eventSession === null)
        {
          return ApiResponse::notFound('EventSession');
        }

        return ApiResponse::success($eventSession->toDto());
      });
    }

    public function add(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        $eventSession = $this->hydrateFromRequest(new EventSession(), $request);

        if (!$eventSession->save())
        {
          return ApiResponse::badRequest('Failed to save eventSession');
        }

        return ApiResponse::created($eventSession->getId());
      });
    }

    public function update(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        $eventSession = EventSession::get($this->getId($request));

        if ($eventSession === null)
        {
          return ApiResponse::notFound('EventSession');
        }

        $eventSession = $this->hydrateFromRequest($eventSession, $request);

        if (!$eventSession->save())
        {
          return ApiResponse::badRequest('Failed to update eventSession');
        }

        return ApiResponse::success(['id' => $eventSession->getId()]);
      });
    }

    public function delete(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        EventSession::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    public function reorder(WP_REST_Request $request): WP_REST_Response
    {
      return $this->execute(function() use ($request)
      {
        $eventRefId = (int)$request['eventRefId'];
        $eventSessionIds = $this->getParams($request)['eventSessionIds'] ?? [];

        EventSession::reorder($eventRefId, $eventSessionIds);

        return ApiResponse::noContent();
      });
    }

    private function hydrateFromRequest(EventSession $session, WP_REST_Request $request): EventSession
    {
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