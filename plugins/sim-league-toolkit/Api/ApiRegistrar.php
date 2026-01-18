<?php

  namespace SLTK\Api;

  class ApiRegistrar {

    public const string API_NAMESPACE = 'sltk/v1';

    public static function init(): void {
      add_action('rest_api_init', [self::class, 'registerRoutes']);
    }

    public static function registerRoutes(): void {
      global $wp;

      if (!str_contains($wp->request, self::API_NAMESPACE)) {
        return;
      }

      $apiController = null;


      if (str_contains($wp->request, ResourceNames::CHAMPIONSHIP)) {
        $apiController = new ChampionshipApiController();
      }

      if (str_contains($wp->request, ResourceNames::CHAMPIONSHIP_EVENT)) {
        $apiController = new ChampionshipEventApiController();
      }

      if (str_contains($wp->request, ResourceNames::DRIVER_CATEGORY)) {
        $apiController = new DriverCategoryApiController();
      }

      if (str_contains($wp->request, ResourceNames::EVENT_CLASS)) {
        $apiController = new EventClassesApiController();
      }

      if (str_contains($wp->request, ResourceNames::GAME)) {
        $apiController = new GameApiController();
      }

      if (str_contains($wp->request, ResourceNames::RACE_NUMBER)) {
        $apiController = new RaceNumberApiController();
      }

      if (str_contains($wp->request, ResourceNames::RULE_SET)) {
        $apiController = new RuleSetApiController();
      }

      if(str_contains($wp->request, ResourceNames::SCORING_SET)) {
        $apiController = new ScoringSetApiController();
      }

      if (str_contains($wp->request, ResourceNames::SERVER)) {
        $apiController = new ServerApiController();
      }


      if (is_subclass_of($apiController, ApiController::class) || is_subclass_of($apiController, LookupApiController::class) || is_subclass_of($apiController, BasicApiController::class)) {
        $apiController->registerRoutes();
      }
    }

  }