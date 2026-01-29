<?php

  namespace SLTK\Api\Traits;

  use SLTK\Core\Constants;
  use WP_Error;
  use WP_REST_Request;
  use WP_REST_Response;

  trait HasPost {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, callable $permissionCallback, callable $responseCallback): void;
    abstract protected function getRestForbiddenError(): WP_Error;

    abstract protected function onPost(WP_REST_Request $request): WP_REST_Response;

    public function post(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        return $this->onPost($request);
      });
    }

    public function canPost(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function registerPostRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute("$resourceName", 'POST', [$this, 'canPost'], [$this, 'post']);
    }
  }
