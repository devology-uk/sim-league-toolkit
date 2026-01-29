<?php

  namespace SLTK\Api;

  use Exception;
  use WP_Error;
  use WP_REST_Request;
  use WP_REST_Response;

  abstract class ApiController {
    protected const string NAMESPACE = ApiRegistrar::API_NAMESPACE;

    private string $resourceName;

    public function __construct(string $resourceName) {
      $this->resourceName = $resourceName;
    }

    protected function getRestForbiddenError(): WP_Error {
      return new WP_Error(
        'rest_forbidden',
        esc_html__('You do not have permission to access this resource.', 'sim-league-toolkit'),
        ['status' => $this->getAuthorisationStatusCode()]
      );
    }

    public abstract function registerRoutes(): void;

    protected function execute(callable $action): WP_REST_Response {
      try {
        return $action();
      } catch (Exception $e) {
        return ApiResponse::serverError($e->getMessage());
      }
    }

    protected function getAuthorisationStatusCode(): int {
      return is_user_logged_in() ? 403 : 401;
    }

    protected function getId(WP_REST_Request $request): int {
      return (int)$request['id'];
    }

    protected function getParams(WP_REST_Request $request): array {
      return $request->get_json_params();
    }

    protected function getResourceName(): string {
      return '/' . $this->resourceName;
    }

    protected function registerRoute(string $route, string|array $methods, callable $permissionCallback, callable $responseCallback): void {
      register_rest_route(
        self::NAMESPACE,
        $route,
        [
          [
            'methods' => $methods,
            'callback' => $responseCallback,
            'permission_callback' => $permissionCallback,
          ]
        ]
      );
    }
  }