<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasDelete;
  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Domain\EventClass;
  use WP_REST_Request;
  use WP_REST_Response;

  class EventClassApiController extends ApiController {
    use HasDelete, HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::EVENT_CLASS);
    }

    public function registerRoutes(): void {$this->registerDeleteRoute();
      $this->registerGetRoute();
      $this->registerGetByIdRoute();
      $this->registerPostRoute();
      $this->registerPutRoute();
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        EventClass::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = EventClass::list();

        return ApiResponse::success(
          array_map(fn($i) => $i->toDto(), $data)
        );
      });
    }


    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = EventClass::get($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('EventClass');
        }

        return ApiResponse::success($data->toDto());
      });
    }



    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new EventClass(), $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to save Event Class', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $entity = EventClass::get($this->getId($request));

        if ($entity === null) {
          return ApiResponse::notFound('EventClass');
        }

        $entity = $this->hydrateFromRequest($entity, $request);

        if (!$entity->save()) {
          return ApiResponse::badRequest(esc_html__('Failed to update Event Class', 'sim-league-toolkit'));
        }

        return ApiResponse::success(['id' => $entity->getId()]);
      });
    }

    private function hydrateFromRequest(EventClass $entity, WP_REST_Request $request): EventClass {
      $params = $this->getParams($request);

      $entity->setCarClass($params['carClass']);
      $entity->setDriverCategoryId((int)$params['driverCategoryId']);
      $entity->setGameId((int)$params['gameId']);
      $entity->setIsSingleCarClass((bool)$params['isSingleCarClass']);
      $entity->setName($params['name']);

      $singleCarId = $params['singleCarId'];
      if (isset($singleCarId) && (int)$singleCarId > 0) {
        $entity->setSingleCarId((int)$singleCarId);
      }
      return $entity;
    }
  }