<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Core\Constants;
  use SLTK\Domain\RuleSet;
  use SLTK\Domain\RuleSetRule;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class RuleSetRuleApiController extends ApiController {
    private const string RESOURCE_BASE = '/' . ResourceNames::RULE_SET_RULE;

    /**
     * @throws Exception
     */
    public function delete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      RuleSetRule::delete($id);

      return rest_ensure_response(true);
    }

    /**
     * @throws Exception
     */
    public function get(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::listRules($id);

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = rest_ensure_response($item->toDto());
      }

      return rest_ensure_response($responseData);
    }

    /**
     * @throws Exception
     */
    public function getById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::getRuleById($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function post(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $ruleSetRule = new RuleSetRule();
      $ruleSetRule->setRuleSetId($data->ruleSetId);
      $ruleSetRule->setRule($data->rule);

      if (isset($data->id) && $data->id > 0) {
        $ruleSetRule->id = $data->id;
      }

      $ruleSetRule->save();

      return new WP_REST_Response($ruleSetRule, 200);
    }

    public function registerRoutes(): void {
      $this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    private function registerDeleteRoute(): void {
      register_rest_route(self::NAMESPACE,
        self::RESOURCE_BASE . '/(?P<id>\d+)',
        [
          [
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => [$this, 'delete'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerGetRoute(): void {
      register_rest_route(self::NAMESPACE,
        self::RESOURCE_BASE . '/(?P<id>\d+)',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerGetByIdRoute(): void {
      register_rest_route(self::NAMESPACE,
        self::RESOURCE_BASE . '/(?P<id>\d+)',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'getById'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerPostRoute(): void {
      register_rest_route(self::NAMESPACE,
        self::RESOURCE_BASE,
        [
          [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'post'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }
  }