<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Domain\RuleSet;
  use WP_REST_Request;
  use WP_REST_Response;

  class RuleSetApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::RULE_SET);
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
        RuleSet::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = RuleSet::list();

        return ApiResponse::success(
          array_map(fn($i) => $i->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = RuleSet::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('RuleSet');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new RuleSet(), $request);

        $entity->save();

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = RuleSet::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('RuleSet');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        $entity->save();

        return ApiResponse::noContent();
      });

    }

    private function hydrateFromRequest(RuleSet $entity, WP_REST_Request $request): RuleSet {
      $params = $this->getParams($request);

      $entity->setName($params['name']);
      $entity->setDescription($params['description']);

      return $entity;
    }
  }