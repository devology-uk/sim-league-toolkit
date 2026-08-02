<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Constants;
  use SLTK\Domain\StandaloneResultPenalty;
  use WP_REST_Request;
  use WP_REST_Response;

  class StandaloneResultPenaltyApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::STANDALONE_RESULT_PENALTY);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerRoute(ResourceNames::STANDALONE_SESSION_RESULT . '/' . Constants::ROUTE_PATTERN_ID . '/penalties', 'GET', [$this, 'canGet'], [$this, 'get']);
      $this->registerGetByIdRoute();
      $this->registerRoute(ResourceNames::STANDALONE_SESSION_RESULT . '/' . Constants::ROUTE_PATTERN_ID . '/penalties', 'POST', [$this, 'canPost'], [$this, 'post']);
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        StandaloneResultPenalty::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = StandaloneResultPenalty::listByResult($this->getId($request));

        return ApiResponse::success(array_map(fn($p) => $p->toDto(), $data));
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = StandaloneResultPenalty::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('StandaloneResultPenalty');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = $this->hydrateFromRequest(new StandaloneResultPenalty(), $request);
        $entity->setStandaloneSessionResultId($this->getId($request));
        $entity->save();

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = StandaloneResultPenalty::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('StandaloneResultPenalty');
        }

        $entity = $this->hydrateFromRequest($entity, $request);
        $entity->save();

        return ApiResponse::noContent();
      });
    }

    private function hydrateFromRequest(StandaloneResultPenalty $entity, WP_REST_Request $request): StandaloneResultPenalty {
      $params = $this->getParams($request);

      $entity->setReason($params['reason'] ?? '');
      $entity->setPenaltySeconds(isset($params['penaltySeconds']) ? (int)$params['penaltySeconds'] : null);

      return $entity;
    }
  }
