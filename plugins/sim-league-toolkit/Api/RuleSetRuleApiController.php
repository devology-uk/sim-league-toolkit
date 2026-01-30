<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Domain\RuleSet;
  use SLTK\Domain\RuleSetRule;
  use WP_REST_Request;
  use WP_REST_Response;

  class RuleSetRuleApiController extends ApiController {
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

        RuleSet::deleteRule($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = RuleSet::listRules($this->getId($request));

        return ApiResponse::success(
          array_map(fn($i) => $i->toDto(), $data)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $data = RuleSet::getRuleById($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('RuleSetRule');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new RuleSetRule(), $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Rule', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = RuleSetRule::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('RuleSetRule');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Rule', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function hydrateFromRequest(RuleSetRule $entity, WP_REST_Request $request): RuleSetRule {
      $params = $this->getParams($request);

      $entity->setRuleSetId((int)$params['ruleSetId']);
      $entity->setRule($params['rule']);

      return $entity;
    }
  }