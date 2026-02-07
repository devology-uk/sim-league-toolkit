<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Constants;
  use SLTK\Domain\RuleSet;
  use SLTK\Domain\RuleSetRule;
  use WP_REST_Request;
  use WP_REST_Response;

  class RuleSetRuleApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::RULE_SET_RULE);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerRoute(ResourceNames::RULE_SET .'/' . Constants::ROUTE_PATTERN_ID . '/rules', 'GET', [$this, 'canGet'], [$this, 'get']);
      $this->registerGetByIdRoute();
      $this->registerRoute(ResourceNames::RULE_SET .'/' . Constants::ROUTE_PATTERN_ID . '/rules', 'POST', [$this, 'canPost'], [$this, 'post']);
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

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
        $ruleSet = RuleSet::get($this->getId($request));

        if (!$ruleSet->saveRule($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to save Rule', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = RuleSet::getRuleById($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('RuleSetRule');
        }

        $entity = $this->hydrateFromRequest($entity, $request);
        $ruleSet = RuleSet::get($this->getId($request));

        if (!$ruleSet->saveRule($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to update Rule', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function hydrateFromRequest(RuleSetRule $entity, WP_REST_Request $request): RuleSetRule {
      $params = $this->getParams($request);

      $entity->setRuleSetId($this->getId($request));
      $entity->setRule($params['rule']);

      return $entity;
    }
  }