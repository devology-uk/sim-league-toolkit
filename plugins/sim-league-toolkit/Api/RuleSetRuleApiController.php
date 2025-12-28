<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Domain\RuleSet;
  use SLTK\Domain\RuleSetRule;
  use WP_REST_Request;
  use WP_REST_Response;

  class RuleSetRuleApiController extends BasicApiController {

    public function __construct() {
      parent::__construct(ResourceNames::RULE_SET_RULE);
    }

    /**
     * @throws Exception
     */
    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      RuleSetRule::delete($id);

      return rest_ensure_response(true);
    }

    /**
     * @throws Exception
     */
    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::listRules($id);

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = $item->toDto();
      }

      return rest_ensure_response($responseData);
    }

    /**
     * @throws Exception
     */
    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::getRuleById($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $ruleSetRule = new RuleSetRule();
      $ruleSetRule->setRuleSetId($data->ruleSetId);
      $ruleSetRule->setRule($data->rule);

      if (isset($data->id) && $data->id > 0) {
        $ruleSetRule->id = $data->id;
      }

      $ruleSetRule->save();

      return rest_ensure_response($ruleSetRule);
    }

    protected function onRegisterRoutes(): void {
    }
  }