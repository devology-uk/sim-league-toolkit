<?php

  namespace SLTK\Api\Traits;

  use SLTK\Core\Constants;
  use WP_Error;
  use WP_REST_Request;
  use WP_REST_Response;

  trait HasGetById {
    abstract protected function execute(callable $action): WP_REST_Response;
    abstract protected function getResourceName(): string;
    abstract protected function registerRoute(string $route, string|array $methods, callable|array $permissionCallback, callable|array $responseCallback): void;
    abstract protected function getRestForbiddenError(): WP_Error;

    abstract protected function onGetById(WP_REST_Request $request): WP_REST_Response;

    public function getById(WP_REST_Request $request): WP_REST_Response {
      return $this->onGetById($request);
    }

    public function canGetById(): bool {
      return true;
    }

    protected function registerGetByIdRoute(): void {
      $resourceName = $this->getResourceName();
      $this->registerRoute($resourceName . '/' . Constants::ROUTE_PATTERN_ID, 'GET', [$this, 'canGetById'], [$this, 'getById']);
    }
  }

