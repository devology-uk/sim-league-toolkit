<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Enums\ResultStatus;
  use SLTK\Domain\Championship;
  use SLTK\Domain\ChampionshipEntry;
  use SLTK\Domain\ChampionshipSessionResult;
  use SLTK\Domain\ResultPointsCalculator;
  use WP_REST_Request;
  use WP_REST_Response;

  class ChampionshipSessionResultApiController extends ApiController {
    use HasDelete, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP_SESSION_RESULT);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetByIdRoute();
      $this->registerPutRoute();
      $this->registerRoute('/' . ResourceNames::EVENT_SESSION . '/(?P<eventSessionId>\d+)/championship-results', 'GET', [$this, 'canListByEventSession'], [$this, 'listByEventSession']);
      $this->registerRoute('/' . ResourceNames::EVENT_SESSION . '/(?P<eventSessionId>\d+)/championship-results', 'POST', [$this, 'canPost'], [$this, 'post']);
    }

    public function canListByEventSession(): bool {
      return true;
    }

    public function listByEventSession(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = ChampionshipSessionResult::listByEventSession((int)$request['eventSessionId']);

        return ApiResponse::success(array_map(fn($r) => $r->toDto(), $data));
      });
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        ChampionshipSessionResult::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = ChampionshipSessionResult::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('ChampionshipSessionResult');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = new ChampionshipSessionResult();
        $entity->setEventSessionId((int)$request['eventSessionId']);
        $entity = $this->hydrateFromRequest($entity, $request);
        $entity->save();

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = ChampionshipSessionResult::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('ChampionshipSessionResult');
        }

        $entity = $this->hydrateFromRequest($entity, $request);
        $entity->save();

        return ApiResponse::noContent();
      });
    }

    private function hydrateFromRequest(ChampionshipSessionResult $entity, WP_REST_Request $request): ChampionshipSessionResult {
      $params = $this->getParams($request);

      $entity->setChampionshipEntryId((int)$params['championshipEntryId']);
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

    private function calculatePoints(ChampionshipSessionResult $entity): ?int {
      $entry = ChampionshipEntry::get($entity->getChampionshipEntryId());

      if ($entry === null) {
        return null;
      }

      $championship = Championship::get($entry->getChampionshipId());

      if ($championship === null) {
        return null;
      }

      return ResultPointsCalculator::calculate($championship->getScoringSetId(), $entity->getPosition());
    }
  }
