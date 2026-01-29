<?php

  namespace SLTK\Api;

  use Exception;
  use SLTK\Api\BasicApiController;
  use SLTK\Domain\ServerSetting;
  use WP_REST_Request;
  use WP_REST_Response;

  class ServerSettingApiController extends ApiController {

    /**
     * @throws Exception
     */
    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      throw new Exception('Not supported');
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $serverSetting = $this->hydrateFromRequest(new ServerSetting(), $request);
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {

    }

    protected function onRegisterRoutes(): void {

    }

    private function hydrateFromRequest(ServerSetting $serverSetting, WP_REST_Request $request): ServerSetting {
      $params = $this->getParams($request);

      $serverSetting->setSettingName($params['settingName']);
      $serverSetting->setSettingValue($params['settingValue']);

      return $serverSetting;
    }
  }