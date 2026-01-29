<?php

  namespace SLTK\Api\Traits;

  use WP_REST_Request;
  use WP_REST_Response;

  trait HasUpdate {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, string $callbackName): void;

    abstract protected function onPut(WP_REST_Request $request): WP_REST_Response;

    protected function get(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        return $this->onPut($request);
      });
    }

    protected function registerPutRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute("$resourceName/(?P<id>\\d+)", 'PUT', 'put');
    }
  }