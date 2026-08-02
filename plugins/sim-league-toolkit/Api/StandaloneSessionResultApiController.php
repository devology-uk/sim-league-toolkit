<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Enums\ResultStatus;
  use SLTK\Domain\ResultPointsCalculator;
  use SLTK\Domain\StandaloneEvent;
  use SLTK\Domain\StandaloneEventEntry;
  use SLTK\Domain\StandaloneSessionResult;
  use WP_REST_Request;
  use WP_REST_Response;

  class StandaloneSessionResultApiController extends ApiController {
    use HasDelete, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::STANDALONE_SESSION_RESULT);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetByIdRoute();
      $this->registerPutRoute();
      $this->registerRoute('/' . ResourceNames::EVENT_SESSION . '/(?P<eventSessionId>\d+)/standalone-results', 'GET', [$this, 'canListByEventSession'], [$this, 'listByEventSession']);
      $this->registerRoute('/' . ResourceNames::EVENT_SESSION . '/(?P<eventSessionId>\d+)/standalone-results', 'POST', [$this, 'canPost'], [$this, 'post']);
    }

    public function canListByEventSession(): bool {
      return true;
    }

    public function listByEventSession(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = StandaloneSessionResult::listByEventSession((int)$request['eventSessionId']);

        return ApiResponse::success(array_map(fn($r) => $r->toDto(), $data));
      });
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        StandaloneSessionResult::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = StandaloneSessionResult::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('StandaloneSessionResult');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = new StandaloneSessionResult();
        $entity->setEventSessionId((int)$request['eventSessionId']);
        $entity = $this->hydrateFromRequest($entity, $request);
        $entity->save();

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = StandaloneSessionResult::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('StandaloneSessionResult');
        }

        $entity = $this->hydrateFromRequest($entity, $request);
        $entity->save();

        return ApiResponse::noContent();
      });
    }

    private function hydrateFromRequest(StandaloneSessionResult $entity, WP_REST_Request $request): StandaloneSessionResult {
      $params = $this->getParams($request);

      $entity->setStandaloneEventEntryId((int)$params['standaloneEventEntryId']);
      $entity->setPosition(isset($params['position']) ? (int)$params['position'] : null);
      $entity->setTotalTimeMs(isset($params['totalTimeMs']) ? (int)$params['totalTimeMs'] : null);
      $entity->setFastestLapMs(isset($params['fastestLapMs']) ? (int)$params['fastestLapMs'] : null);
      $entity->setSector1TimeMs(isset($params['sector1TimeMs']) ? (int)$params['sector1TimeMs'] : null);
      $entity->setSector2TimeMs(isset($params['sector2TimeMs']) ? (int)$params['sector2TimeMs'] : null);
      $entity->setSector3TimeMs(isset($params['sector3TimeMs']) ? (int)$params['sector3TimeMs'] : null);
      $entity->setLapsCompleted((int)($params['lapsCompleted'] ?? 0));
      $entity->setStatus(ResultStatus::tryFrom($params['status'] ?? '') ?? ResultStatus::Finished);
      $entity->setPoints($this->calculatePoints($entity));

      return $entity;
    }

    private function calculatePoints(StandaloneSessionResult $entity): ?int {
      $entry = StandaloneEventEntry::get($entity->getStandaloneEventEntryId());

      if ($entry === null) {
        return null;
      }

      $standaloneEvent = StandaloneEvent::get($entry->getStandaloneEventId());

      if ($standaloneEvent === null) {
        return null;
      }

      return ResultPointsCalculator::calculate($standaloneEvent->getScoringSetId(), $entity->getPosition());
    }
  }
