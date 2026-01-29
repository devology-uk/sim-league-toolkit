<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Domain\DriverCategory;
  use WP_REST_Request;
  use WP_REST_Response;

  class DriverCategoryApiController extends ApiController {
    use HasGetById, HasGet;

    public function __construct() {
      parent::__construct(ResourceNames::DRIVER_CATEGORY);
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $driverCategories = DriverCategory::list();

        return ApiResponse::success(
          array_map(fn($s) => $s->toDto(), $driverCategories)
        );
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $driverCategory = DriverCategory::get($this->getId($request));

        if ($driverCategory === null) {
          return ApiResponse::notFound('DriverCategory');
        }

        return ApiResponse::success($driverCategory->toDto());
      });
    }

    public function registerRoutes(): void {
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
    }
  }