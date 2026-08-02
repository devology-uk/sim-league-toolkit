<?php

  namespace SLTK\Api\Traits;

  use SLTK\Core\Constants;
  use WP_REST_Request;
  use WP_REST_Response;

  trait HasGet {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, callable|array $permissionCallback, callable|array $responseCallback): void;

    abstract protected function onGet(WP_REST_Request $request): WP_REST_Response;

    public function get(WP_REST_Request $request): WP_REST_Response {
      return $this->onGet($request);
    }

    public function canGet(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    protected function registerGetRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute($resourceName . 's', 'GET', [$this,'canGet'], [$this,'get']);
    }
  }