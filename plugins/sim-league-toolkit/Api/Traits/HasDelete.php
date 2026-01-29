<?php

  namespace SLTK\Api\Traits;

  use SLTK\Api\ApiResponse;
  use WP_REST_Request;
  use WP_REST_Response;

  trait HasDelete {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, string $callbackName): void;

    abstract protected function onDelete(WP_REST_Request $request): void;

    public function delete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $this->onDelete($request);
        return ApiResponse::noContent();
      });
    }

    protected function registerDeleteRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute("$resourceName/(?P<id>\\d+)", 'DELETE', 'delete');
    }
  }

