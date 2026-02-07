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
      $id = $request->get_param('id');

      $data = RuleSet::get($id);

      return rest_ensure_response($data->toDto());
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new RuleSet(), $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Rule Set', 'sim-league-toolkit'));
        }

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

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Rule Set', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });

    }

    private function hydrateFromRequest(RuleSet $entity, WP_REST_Request $request): RuleSet {
      $params = $this->getParams($request);

      $entity->setName($params['name']);
      $entity->setDescription($params['description']);

      return $entity;
    }
  }