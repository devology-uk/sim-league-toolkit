<?php

  namespace SLTK\Api\Traits;

  use WP_REST_Request;
  use WP_REST_Response;

  trait HasGet {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, callable $permissionCallback, callable $responseCallback): void;

    abstract protected function onGet(WP_REST_Request $request): WP_REST_Response;

    public function get(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        return $this->onGet($request);
      });
    }

    public function canGet(): bool {
      return true;
    }

    protected function registerGetRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute($resourceName, 'GET', [$this, 'canGet'], [$this,'get']);
    }
  }