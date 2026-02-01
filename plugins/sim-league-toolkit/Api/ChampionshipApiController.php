<?php

  namespace SLTK\Api;

  use DateTime;
  use DateTimeInterface;
  use DateTimeZone;
  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Domain\Championship;
  use WP_REST_Request;
  use WP_REST_Response;

  class ChampionshipApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): void {
      $this->execute(function () use ($request) {
        Championship::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Championship::list();

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Championship::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('Championship');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new Championship(), $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Championship', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = Championship::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('Championship');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Championship', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function hydrateFromRequest(Championship $entity, WP_REST_Request $request): Championship {
      $params = $this->getParams($request);

      $entity->setAllowEntryChange((int)$params['allowEntryChange']);
      $entity->setBannerImageUrl($params['bannerImageUrl']);
      $entity->setChampionshipType($params['championshipType']);
      $entity->setDescription($params['description']);
      $entity->setEntryChangeLimit((bool)$params['entryChangeLimit']);
      $entity->setGameId((int)$params['gameId']);
      $entity->setIsActive((bool)$params['isActive'] ?? false);
      $entity->setName($params['name']);
      $entity->setPlatformId((int)$params['platformId']);
      $entity->setResultsToDiscard((int)$params['resultsToDiscard']);
      $entity->setRuleSetId((int)$params['ruleSetId']);
      $entity->setScoringSetId((int)$params['scoringSetId']);
      $startDate = DateTime::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $params['startDate'], new DateTimeZone('UTC'));
      $entity->setStartDate($startDate);
      $entity->setTrackMasterTrackId((int)$params['trackMasterTrackId'] ?? null);
      $entity->setTrackMasterTrackLayoutId((int)$params['trackMasterTrackLayoutId'] ?? null);
      $entity->setTrophiesAwarded((bool)$params['trophiesAwarded'] ?? false);

      return $entity;
    }
  }