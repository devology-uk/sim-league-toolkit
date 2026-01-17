<?php

  namespace SLTK\Api;

  use DateTime;
  use DateTimeInterface;
  use DateTimeZone;
  use Exception;
  use JsonException;
  use SLTK\Core\BannerImageProvider;
  use SLTK\Domain\ChampionshipEvent;
  use WP_REST_Request;
  use WP_REST_Response;

  class ChampionshipEventApiController extends BasicApiController {
    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP_EVENT);
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $result = true;
      try {
        ChampionshipEvent::delete($id);
      } catch (Exception) {
        $result = false;
      }

      return rest_ensure_response($result);
    }

    /**
     * @throws Exception
     */
    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = ChampionshipEvent::list($id);

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

      $data = ChampionshipEvent::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     */
    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $entity = new ChampionshipEvent();
      if(empty($data->bannerImageUrl)) {
        $data->bannerImageUrl = BannerImageProvider::getRandomBannerImageUrl();
      }

      $entity->setBannerImageUrl($data->bannerImageUrl);
      $entity->setChampionshipId($data->championshipId);
      $entity->setDescription($data->description);
      $entity->setName($data->name);
      $entity->setRuleSetId($data->ruleSetId);
      $startDateTime = DateTime::createFromFormat(DateTimeInterface::RFC3339_EXTENDED, $data->startDateTime, new DateTimeZone('UTC'));
      $entity->setStartDateTime($startDateTime);
      $entity->setTrackId($data->trackId);

      if (isset($data->trackLayoutId)) {
        $entity->setTrackLayoutId($data->trackLayoutId);
      }

      if (isset($data->id) && $data->id > 0) {
        $entity->setId($data->id);
      }

      $entity->save();

      return rest_ensure_response($entity);
    }

    protected function onRegisterRoutes(): void {
    }
  }