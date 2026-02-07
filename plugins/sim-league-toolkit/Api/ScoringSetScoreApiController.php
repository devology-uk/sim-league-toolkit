<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Constants;
  use SLTK\Domain\ScoringSet;
  use SLTK\Domain\ScoringSetScore;
  use WP_REST_Request;
  use WP_REST_Response;

  class ScoringSetScoreApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::SCORING_SET_SCORE);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerRoute(ResourceNames::SCORING_SET . '/' . Constants::ROUTE_PATTERN_ID . '/scores', 'GET', [$this, 'canGet'], [$this, 'get']);
      $this->registerGetByIdRoute();
      $this->registerRoute(ResourceNames::SCORING_SET . '/' . Constants::ROUTE_PATTERN_ID . '/scores', 'POST', [$this, 'canPost'], [$this, 'post']);
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        ScoringSet::deleteScore($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = ScoringSet::listScores($this->getId($request));

        return ApiResponse::success(
          array_map(fn($i) => $i->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $data = ScoringSet::getScoreById($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('ScoringSetScore');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new ScoringSetScore(), $request);
        $scoringSet = ScoringSet::get($entity->getScoringSetId());

        if (!$scoringSet->saveScore($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to save Score', 'sim-league-toolkit'));
        }

        return ApiResponse::success($entity);
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = ScoringSet::getScoreById($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('ScoringSetScore');
        }

        $entity = $this->hydrateFromRequest($entity, $request);
        $scoringSet = ScoringSet::get($entity->getScoringSetId());

        if (!$scoringSet->saveScore($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to update Score', 'sim-league-toolkit'));
        }

        return ApiResponse::success($entity);
      });
    }

    private function hydrateFromRequest(ScoringSetScore $entity, WP_REST_Request $request): ScoringSetScore {
      $params = $this->getParams($request);

      $entity->setScoringSetId($this->getId($request));
      $entity->setPosition((int)$params['position']);
      $entity->setPoints($params['points']);

      return $entity;
    }
  }