<?php

  namespace SLTK\Api;

  use SLTK\Api\Traits\HasGet;
  use SLTK\Api\Traits\HasGetById;
  use SLTK\Api\Traits\HasPost;
  use SLTK\Api\Traits\HasPut;
  use SLTK\Core\Constants;
  use SLTK\Domain\Server;
  use SLTK\Domain\ServerSetting;
  use WP_REST_Request;
  use WP_REST_Response;

  class ServerSettingApiController extends ApiController {
    use HasGet, HasGetById, HasPost, HasPut;

    public function __construct() {
      parent::__construct(ResourceNames::SERVER_SETTING);
    }

    public function registerRoutes(): void {
      $this->registerRoute(ResourceNames::SERVER .'/' . Constants::ROUTE_PATTERN_ID . '/settings', 'GET', [$this, 'canGet'], [$this, 'get']);
      $this->registerGetByIdRoute();
      $this->registerRoute(ResourceNames::SERVER .'/' . Constants::ROUTE_PATTERN_ID . '/settings', 'POST', [$this, 'canPost'], [$this, 'post']);
      $this->registerPutRoute();
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $data = Server::listSettings($this->getId($request));

        return ApiResponse::success(array_map(fn($i) => $i->toDto(), $data));
      });
    }

    protected function onGetById(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $data = Server::getSettingById($this->getId($request));

        if ($data === null) {
          return ApiResponse::notFound('ServerSetting');
        }

        return ApiResponse::success($data->toDto());
      });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {

        $entity = $this->hydrateFromRequest(new ServerSetting(), $request);

        if (!Server::addSetting($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to save Server Setting', 'sim-league-toolkit'));
        }

        return ApiResponse::created($entity->getId());
      });
    }

    protected function onPut(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $params = $this->getParams($request);
        $entity = Server::getSetting($this->getId($request), $params['settingName']);

        if ($entity === null) {
          return ApiResponse::notFound('ServerSetting');
        }

        $entity->setSettingValue($params['settingValue']);

        if (!Server::saveSetting($entity)) {
          return ApiResponse::badRequest(esc_html__('Failed to update Server Setting', 'sim-league-toolkit'));
        }

        return ApiResponse::success($entity);
      });
    }


    private function hydrateFromRequest(ServerSetting $serverSetting, WP_REST_Request $request): ServerSetting {
      $params = $this->getParams($request);

      $serverSetting->setServerId($request->get_param('id'));
      $serverSetting->setSettingName($params['settingName']);
      $serverSetting->setSettingValue($params['settingValue']);

      return $serverSetting;
    }
  }