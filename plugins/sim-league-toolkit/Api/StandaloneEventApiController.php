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
  use SLTK\Core\BannerImageProvider;
  use SLTK\Domain\StandaloneEvent;
  use WP_REST_Request;
  use WP_REST_Response;

  class StandaloneEventApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::STANDALONE_EVENT);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        StandaloneEvent::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () {
        $data = StandaloneEvent::list();

        return ApiResponse::success(
          array_values(array_map(fn($s) => $s->toDto(), $data))
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = StandaloneEvent::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('Standalone Event');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = $this->hydrateFromRequest(new StandaloneEvent(), $request);

        if (empty($entity->getBannerImageUrl())) {
          $entity->setBannerImageUrl(BannerImageProvider::getRandomBannerImageUrl());
        }

        $entity->save();

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = StandaloneEvent::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('Standalone Event');
        }

        $this->hydrateFromRequest($entity, $request);
        $entity->save();

        return ApiResponse::noContent();
      });
    }

    private function hydrateFromRequest(StandaloneEvent $entity, WP_REST_Request $request): StandaloneEvent {
      $params = $this->getParams($request);

      $entity->setName($params['name']);
      $entity->setDescription($params['description']);
      $entity->setBannerImageUrl($params['bannerImageUrl'] ?? '');
      $entity->setGameId((int)$params['gameId']);
      $entity->setTrackId((int)$params['trackId']);
      $entity->setTrackLayoutId(
        isset($params['trackLayoutId']) && $params['trackLayoutId'] > 0 ? (int)$params['trackLayoutId'] : null
      );
      $entity->setEventDate(
        DateTime::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $params['eventDate'], new DateTimeZone('UTC'))
      );
      $entity->setIsActive((bool)($params['isActive'] ?? false));
      $entity->setIsPublic(false);
      $entity->setStartTime($params['startTime'] ?? '');
      $entity->setMaxEntrants((int)($params['maxEntrants'] ?? 0));
      $entity->setScoringSetId(
        isset($params['scoringSetId']) && $params['scoringSetId'] > 0 ? (int)$params['scoringSetId'] : null
      );
      $entity->setRuleSetId(
        isset($params['ruleSetId']) && $params['ruleSetId'] > 0 ? (int)$params['ruleSetId'] : null
      );

      return $entity;
    }
  }
