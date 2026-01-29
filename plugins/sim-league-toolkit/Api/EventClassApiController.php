<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Domain\EventClass;
  use WP_REST_Request;
  use WP_REST_Response;

  class EventClassApiController extends BasicApiController {
    public function __construct() {
      parent::__construct(ResourceNames::EVENT_CLASS);
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $result = true;
      try {
        EventClass::delete($id);
      } catch (Exception) {
        $result = false;
      }

      return rest_ensure_response($result);
    }

    /**
     * @throws Exception
     */
    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = EventClass::list();

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    /**
     * @throws Exception
     */
    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = EventClass::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $eventClass = new EventClass();
      $eventClass->setCarClass($data->carClass);
      $eventClass->setDriverCategoryId($data->driverCategoryId);
      $eventClass->setGameId($data->gameId);
      $eventClass->setIsSingleCarClass($data->isSingleCarClass);
      $eventClass->setName($data->name);

      if (isset($data->singleCarId) && $data->singleCarId > 0) {
        $eventClass->setSingleCarId($data->singleCarId);
      }

      if ($data->id > 0) {
        $eventClass->setId($data->id);
      }

      $eventClass->save();

      return rest_ensure_response($eventClass);
    }

    protected function onRegisterRoutes(): void {
    }
  }