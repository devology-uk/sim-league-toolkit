<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Domain\RuleSet;
  use SLTK\Domain\RuleSetRule;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class RuleSetApiController extends BasicApiController {

    public function __construct() {
      parent::__construct(ResourceNames::RULE_SET);
    }


    /**
     * @throws Exception
     */
    public function deleteRule(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      RuleSet::deleteRule($id);

      return rest_ensure_response(true);
    }

    /**
     * @throws Exception
     */
    public function getRule(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::getRuleById($id);

      return rest_ensure_response($data);
    }

    /**
     * @throws Exception
     */
    public function getRules(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('ruleSetId');

      $data = RuleSet::listRules($id);

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    /**
     * @throws Exception
     */
    public function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      RuleSet::delete($id);

      return rest_ensure_response(true);
    }

    public function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = RuleSet::list();

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    public function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     */
    public function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $entity = new RuleSet();
      $entity->setName($data->name);
      $entity->setDescription($data->description);
      $entity->setType($data->type);

      if (isset($data->id) && $data->id > 0) {
        $entity->setId($data->id);
      }

      $entity->save();

      return rest_ensure_response($entity->toDto());
    }

    /**
     * @throws JsonException
     */
    public function postRule(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $entity = new RuleSetRule();
      $entity->setRuleSetId($data->ruleSetId);
      $entity->setRule($data->rule);

      if (isset($data->id) && $data->id > 0) {
        $entity->setId($data->id);
      }

      $ruleSet = RuleSet::get($entity->getRuleSetId());
      $ruleSet->saveRule($entity);

      return rest_ensure_response($entity->toDto());
    }

    protected function onRegisterRoutes(): void {
      $this->registerDeleteRuleRoute();
      $this->registerGetRulesRoute();
      $this->registerGetRulesRoute();
      $this->registerPostRuleRoute();
    }

    private function registerDeleteRuleRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/rules/(?P<id>[\d]+)',
        [
          [
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => [$this, 'deleteRule'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerGetRuleRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/rules/(?P<id>[\d]+)',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'getRules'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerGetRulesRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<ruleSetId>[\d]+)/rules',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'getRules'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerPostRuleRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/rules',
        [
          [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'postRule'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

  }