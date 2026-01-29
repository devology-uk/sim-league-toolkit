<?php

  namespace SLTK\Api\Traits;

  use WP_REST_Request;
  use WP_REST_Response;

  trait HasList {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, string $callbackName): void;

    abstract protected function onList(WP_REST_Request $request): WP_REST_Response;

    protected function list(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        return $this->onList($request);
      });
    }

    protected function registerListRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute($resourceName, 'GET', 'list');
    }
  }