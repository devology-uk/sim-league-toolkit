<?php

  namespace SLTK\Api;

  use Exception;
  use JsonException;
  use SLTK\Domain\ScoringSet;
  use SLTK\Domain\ScoringSetScore;
  use WP_REST_Request;
  use WP_REST_Response;
  use WP_REST_Server;

  class ScoringSetApiController extends BasicApiController {

    public function __construct() {
      parent::__construct(ResourceNames::SCORING_SET);
    }

    /**
     * @throws Exception
     */
    public function getScores(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      $scoringSet = ScoringSet::get($id);

      $data = $scoringSet->getScores();
      if (empty($data)) {
        return rest_ensure_response($data);
      }

      $responseData = [];

      foreach ($data as $item) {
        $responseData[] = $item->toDto();
      }

      return rest_ensure_response($responseData);
    }

    /**
     * @throws JsonException
     */
    public function postScore(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $newItem = new ScoringSetScore();
      $newItem->setPoints($data->points);
      $newItem->setPosition($data->position);
      $newItem->setScoringSetId($data->scoringSetId);

      if (isset($data->id) && $data->id > 0) {
        $newItem->id = $data->id;
      }

      $scoringSet = ScoringSet::get($data->scoringSetId);
      $scoringSet->saveScore($newItem);

      return rest_ensure_response($newItem);
    }

    /**
     * @throws Exception
     */
    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
      $id = $request->get_param('id');

      ScoringSet::delete($id);

      return rest_ensure_response(true);
    }

    /**
     * @throws Exception
     */
    protected function onGet(WP_REST_Request $request): WP_REST_Response {
      $data = ScoringSet::list();

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

      $data = ScoringSet::get($id);

      return rest_ensure_response($data->toDto());
    }

    /**
     * @throws JsonException
     */
    protected function onPost(WP_REST_Request $request): WP_REST_Response {
      $body = $request->get_body();

      $data = json_decode($body, false, 512, JSON_THROW_ON_ERROR);

      $newItem = new ScoringSet();
      $newItem->setName($data->name);
      $newItem->setDescription($data->description);
      $newItem->setPointsForFastestLap($data->pointsForFastestLap);
      $newItem->setPointsForFinishing($data->pointsForFinishing);
      $newItem->setPointsForPole($data->pointsForPole);
      $newItem->setIsBuiltIn(false);

      if (isset($data->id) && $data->id > 0) {
        $newItem->id = $data->id;
      }

      $newItem->save();

      return rest_ensure_response($newItem);
    }

    protected function onRegisterRoutes(): void {
      $this->registerGetScoresRoute();
      $this->registerPostScoreRoute();
    }

    private function registerGetScoresRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>\d+)/scores',
        [
          [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'getScores'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }

    private function registerPostScoreRoute(): void {
      register_rest_route(self::NAMESPACE,
        $this->getResourceName() . '/(?P<id>\d+)/scores',
        [
          [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'postScore'],
            'permission_callback' => [$this, 'checkPermission'],
          ]
        ]
      );
    }


  }