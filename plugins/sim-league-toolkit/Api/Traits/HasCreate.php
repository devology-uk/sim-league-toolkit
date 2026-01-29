<?php

  namespace SLTK\Api\Traits;

  use WP_REST_Request;
  use WP_REST_Response;

  trait HasCreate {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, string $callbackName): void;

    abstract protected function onPost(WP_REST_Request $request): WP_REST_Response;

    protected function get(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        return $this->onPost($request);
      });
    }

    protected function registerPostRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute("$resourceName", 'POST', 'post');
    }
  }
