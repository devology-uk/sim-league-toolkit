<?php

  namespace SLTK\Api;

  use SLTK\Domain\RaceNumber;
  use WP_REST_Request;
  use WP_REST_Response;

  class RaceNumbersApiController extends ApiController {
    private const string RESOURCE_BASE = '/race-numbers';
    private array $schema;

    public function index(WP_REST_Request $request): WP_REST_Response {
      $raceNumbers = RaceNumber::list();

      if(empty($raceNumbers)) {
        return rest_ensure_response($raceNumbers);
      }

      $responseData = [];

      foreach($raceNumbers as $raceNumber) {
        $responseData[] = rest_ensure_response($raceNumber->toDto());
      }

      return rest_ensure_response($responseData);
    }

    protected function canExecute(): bool {
      return current_user_can('read');
    }

    public function registerRoutes(): void {
      $this->registerListActiveRoute();
    }

    private function getSchema(): array {
      if(!isset($this->schema)) {
        $this->schema = [
          '$schema'    => 'http://json-schema.org/draft-04/schema#',
          'title'      => 'race-number',
          'type'       => 'object',
          'properties' => [
            'userId'          => [
              'description' => esc_html__('The id of the user the race number is allocated to.', 'sim-league-toolkit'),
              'type'        => 'integer',
              'context'     => ['view', 'edit', 'list'],
              'readonly'    => true,
            ],
            'userDisplayName' => [
              'description' => esc_html__('The display name of the user the race number is allocated to.', 'sim-league-toolkit'),
              'type'        => 'integer',
              'context'     => ['view', 'edit', 'list'],
              'readonly'    => true,
            ],
            'raceNumber'      => [
              'description' => esc_html__('The allocated race number.', 'sim-league-toolkit'),
              'type'        => 'integer',
              'context'     => ['view', 'edit', 'list'],
              'readonly'    => false,
            ]
          ]
        ];
      }

      return $this->schema;
    }

    private function registerListActiveRoute(): void {
      register_rest_route(self::NAMESPACE,
                          self::RESOURCE_BASE,
                          [
                            [
                              'methods'             => 'GET',
                              'callback'            => [$this, 'index'],
                              'permission_callback' => [$this, 'checkPermission'],
                            ],
                            'schema' => $this->getSchema()
                          ]
      );
    }
  }