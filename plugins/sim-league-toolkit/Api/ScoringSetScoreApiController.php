<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Domain\ScoringSet;
  use SLTK\Domain\ScoringSetScore;
  use WP_REST_Request;
  use WP_REST_Response;

  class ScoringSetScoreApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): void {
      $this->execute(function () use ($request) {

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

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Score', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = ScoringSetScore::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('ScoringSetScore');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Score', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function hydrateFromRequest(ScoringSetScore $entity, WP_REST_Request $request): ScoringSetScore {
      $params = $this->getParams($request);

      $entity->setScoringSetId((int)$params['scoringSetId']);
      $entity->setScore($params['score']);

      return $entity;
    }
  }