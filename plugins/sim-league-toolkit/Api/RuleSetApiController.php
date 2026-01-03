<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Domain\RuleSet;
  use WP_REST_Request;
  use WP_REST_Response;

  class RuleSetApiController extends BasicApiController {

    public function __construct() {
      parent::__construct(ResourceNames::RULE_SET);
    }

    /**
     * @throws Exception
     */
    public function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      RuleSet::delete($id);

      return rest_ensure_response(true);
    }

    public function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = RuleSet::list();

      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = $item->toDto();
      }

      return rest_ensure_response($responseData);
    }

    public function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = RuleSet::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     */
    public function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $newItem = new RuleSet();
      $newItem->setName($data->name);
      $newItem->setDescription($data->description);
      $newItem->setType($data->type);

      if (isset($data->id) && $data->id > 0) {
        $newItem->id = $data->id;
      }

      $newItem->save();

      return new WP_REST_Response($newItem, 200);
    }

    protected function onRegisterRoutes(): void {

    }
  }