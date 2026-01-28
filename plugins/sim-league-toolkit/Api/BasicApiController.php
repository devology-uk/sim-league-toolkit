<?php

  namespace SLTK\Api;

  use Exception;
  use WP_REST_Request;
  use WP_REST_Response;

  abstract class BasicApiController extends LookupApiController {

    public function __construct(string $resourceName) {
      parent::__construct($resourceName);
    }

    /**
     * @throws Exception
     */
    public function delete(WP_REST_Request $request): WP_REST_Response {
      return $this->onDelete($request);
    }

    /**
     * @throws Exception
     */
    public function post(WP_REST_Request $request): WP_REST_Response {
      return $this->onPost($request);
    }

    /**
     * @throws Exception
     */
    public function put(WP_REST_Request $request): WP_REST_Response {
      return $this->onPut($request);
    }

    public function registerRoutes(): void {
      parent::registerRoutes();
      $resourceName = $this->getResourceName();
      $this->registerDeleteRoute("$resourceName/(?P<id>\\d+)", 'delete');
      $this->registerPostRoute($resourceName, 'post');
      $this->registerPutRoute("$resourceName/(?P<id>\\d+)", 'put');
    }

    protected abstract function onDelete(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onPost(WP_REST_Request $request): WP_REST_Response;

    protected abstract function onPut(WP_REST_Request $request): WP_REST_Response;

  }