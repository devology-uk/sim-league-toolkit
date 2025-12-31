<?php

  namespace SLTK\Api;

  use SLTK\Api\BasicApiController;
  use SLTK\Domain\DriverCategory;
  use WP_REST_Request;
  use WP_REST_Response;

  class DriverCategoryApiController extends LookupApiController {

    public function __construct() {
      parent::__construct(ResourceNames::DRIVER_CATEGORY);
    }
    
    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = DriverCategory::list();

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = $item->toDto();
      }

      return rest_ensure_response($responseData);
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = DriverCategory::get($id);

      return rest_ensure_response($data->toDto());

    }

    protected function onRegisterRoutes(): void {
    }
  }