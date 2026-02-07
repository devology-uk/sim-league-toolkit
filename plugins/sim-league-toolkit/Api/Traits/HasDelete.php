<?php

  namespace SLTK\Api\Traits;

  use SLTK\Core\Constants;
  use WP_Error;
  use WP_REST_Request;
  use WP_REST_Response;

  trait HasDelete {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, callable|array $permissionCallback, callable|array $responseCallback): void;
    abstract protected function getRestForbiddenError(): WP_Error;

    abstract protected function onDelete(WP_REST_Request $request): WP_REST_Response;

    public function delete(WP_REST_Request $request): WP_REST_Response {
        return $this->onDelete($request);
    }

    public function canDelete(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function registerDeleteRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute("$resourceName/(?P<id>\\d+)", 'DELETE', [$this, 'canDelete'], [$this, 'delete']);
    }
  }

