<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Domain\RuleSet;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class RuleSetRuleApiController extends ApiController {
    private const string RESOURCE_BASE = '/' . ResourceNames::RULE_SET_RULE;

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

    public function registerRoutes(): void {
      $this->registerGetRoute();
    }

    protected function canExecute(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
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
  }