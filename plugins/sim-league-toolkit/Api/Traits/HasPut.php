<?php

  namespace SLTK\Api\Traits;

  use SLTK\Core\Constants;
  use WP_REST_Request;
  use WP_REST_Response;

  trait HasPut {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, callable|array $permissionCallback, callable|array $responseCallback): void;

    abstract protected function onPut(WP_REST_Request $request): WP_REST_Response;

    public function put(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        return $this->onPut($request);
      });
    }

    public function canPut(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function registerPutRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute("$resourceName/(?P<id>\\d+)", 'PUT', [$this, 'canPut'], [$this, 'put']);
    }
  }