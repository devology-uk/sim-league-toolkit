<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Database\Repositories\ServerSettingRepository;
  use SLTK\Domain\Server;
  use SLTK\Domain\ServerSetting;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class ServerApiController extends ApiController {

    public function __construct() {
      parent::__construct(ResourceNames::SERVER);
    }

    /**
     * @throws Exception
     */
    public function deleteSetting(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function() use ($request)
      {
        ServerSetting::delete($this->getId($request));

        return ApiResponse::noContent();
      });
    }

    /**
     * @throws Exception
     */
    public function getSettings(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $server = Server::get($id);

      $data = $server->getSettings();

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    /**
     * @throws Exception
     */
    public function getSetting(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Server::getSettingById($id);

      return rest_ensure_response($data);
    }

    /**
     * @throws Exception
     */
    public function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = Server::list();

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function postSetting(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $entity = new ServerSetting();
      $entity->setServerId($data->serverId);
      $entity->setSettingName($data->settingName);
      $entity->setSettingValue($data->settingValue);

      if (isset($data->id) && $data->id > 0) {
        $entity->setId($data->id);
      }

      $owner = Server::get($entity->getServerId());
      $owner->saveSetting($entity);

      return rest_ensure_response($entity->toDto());
    }

    /**
     * @throws Exception
     */
    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      Server::delete($id);

      return rest_ensure_response(true);
    }

    /**
     * @throws Exception
     */
    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Server::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $newItem = new Server();
      $newItem->setGameId($data->gameId);
      $newItem->setIsHostedServer($data->isHostedServer);
      $newItem->setName($data->name);
      $newItem->setPlatformId($data->platformId);

      $newItem->save();

      return rest_ensure_response($newItem);
    }

    protected function onRegisterRoutes(): void {
      $resourceName = $this->getResourceName();
      $this->registerDeleteRoute("$resourceName/settings/(?P<id>[\\d]+)", 'deleteSetting');
      $this->registerGetRoute("$resourceName/settings/(?P<id>[\\d]+)", 'getSetting');
      $this->registerGetRoute("$resourceName/(?P<id>\\d+)/settings", 'getSettings');
      $this->registerPostRoute("$resourceName/settings", 'postSetting');
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {

    }
  }