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
  use SLTK\Domain\ChampionshipEvent;
  use WP_REST_Request;
  use WP_REST_Response;

  class ChampionshipEventApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP_EVENT);
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
        ChampionshipEvent::delete($this->getId($request));
        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = ChampionshipEvent::list();

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = ChampionshipEvent::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('Championship');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new ChampionshipEvent(), $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Championship Event', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = ChampionshipEvent::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('ChampionshipEvent');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Championship Event', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function hydrateFromRequest(ChampionshipEvent $entity, WP_REST_Request $request): ChampionshipEvent {
      $params = $this->getParams($request);

      if(empty($params['bannerImageUrl'])) {
        $entity->setBannerImageUrl(BannerImageProvider::getRandomBannerImageUrl());
      } else {
        $entity->setBannerImageUrl($params['bannerImageUrl']);
      }
      $entity->setChampionshipId((int)$params['championshipId']);
      $entity->setDescription($params['description']);
      $entity->setName($params['name']);
      $entity->setRuleSetId((int)$params['ruleSetId']);
      $startDateTime = DateTime::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $params['startDateTime'], new DateTimeZone('UTC'));
      $entity->setStartDateTime($startDateTime);
      $entity->setTrackId((int)$params['trackId']);

      if (isset($params['trackLayoutId'])) {
        $entity->setTrackLayoutId($params['trackLayoutId']);
      }

      return $entity;
    }
  }