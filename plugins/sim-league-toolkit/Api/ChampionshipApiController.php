<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Domain\Championship;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class ChampionshipApiController extends BasicApiController {
    public function __construct() {
      parent::__construct(ResourceNames::CHAMPIONSHIP);
    }

    /**
     * @throws Exception
     */
    public function deleteChampionshipClass(WP_REST_Request $request): WP_REST_Response {
      $championshipId = $request->get_param('championshipId');
      $eventClassId = $request->get_param('eventClassId');

      Championship::deleteChampionshipClass($championshipId, $eventClassId);

      return rest_ensure_response(true);
    }

    /**
     * @throws Exception
     */
    public function getClasses(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $data = Championship::listClasses($id);

      $responseData = array_map(function ($item) {
        return $item->toDto();
      }, $data);

      return rest_ensure_response($responseData);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function postChampionshipClass(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      Championship::addChampionshipClass($data->championshipId, $data->eventClassId);

      return rest_ensure_response(true);
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $result = true;
      try {
        Championship::delete($id);
      } catch (Exception) {
        $result = false;
      }

      return rest_ensure_response($result);
    }

    /**
     * @throws Exception
     */
    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = Championship::list();

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

      $data = Championship::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     */
    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $entity = new Championship();
      $entity->setAllowEntryChange($data->allowEntryChange);
      $entity->setBannerImageUrl($data->bannerImageUrl);
      $entity->setDescription($data->description);
      $entity->setEntryChangeLimit($data->entryChangeLimit);
      $entity->setGameId($data->gameId);
      $entity->setIsActive($data->isActive ?? false);
      $entity->setIsTrackMasterChampionship($data->isTrackMasterChampionship);
      $entity->setName($data->name);
      $entity->setPlatformId($data->platformId);
      $entity->setResultsToDiscard($data->resultsToDiscard);
      $entity->setRuleSetId($data->ruleSetId);
      $entity->setScoringSetId($data->scoringSetId);
      $entity->setStartDate($data->startDate);
      $entity->setTrackMasterTrackId($data->trackMasterTrackId ?? null);
      $entity->setTrackMasterTrackLayoutId($data->trackMasterTrackLayoutId ?? null);
      $entity->setTrophiesAwarded($data->trophiesAwarded ?? false);

      if ($data->id > 0) {
        $entity->setId($data->id);
      }

      $entity->save();

      return rest_ensure_response($entity);
    }

    protected function onRegisterRoutes(): void {
      $this->registerGetClassesRoute();
      $this->registerPostClassRoute();
      $this->registerDeleteClassRoute();
    }

    private function registerDeleteClassRoute(): void {
      $route = $this->getResourceName() . '/(?P<championshipId>\d+)/classes/(?P<eventClassId>\d+)';
      $this->registerRoute($route, WP_REST_Server::DELETABLE, 'deleteChampionshipClass');
    }

    private function registerGetClassesRoute(): void {
      $route = $this->getResourceName() . '/(?P<id>\d+)/classes';
      $this->registerRoute($route, WP_REST_Server::READABLE, 'getClasses');
    }

    private function registerPostClassRoute(): void {

      $route = $this->getResourceName() . '/classes';
      $this->registerRoute($route, WP_REST_Server::CREATABLE, 'postChampionshipClass');
    }
  }