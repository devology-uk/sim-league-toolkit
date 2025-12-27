<?php

  namespace SLTK\Api;

  class ApiRegistrar {

    private const string API_NAMESPACE = 'sltk/v1';

    public static function init(): void {
      add_action('rest_api_init', [self::class, 'registerRoutes']);
    }

    public static function registerRoutes(): void {
      global $wp;

      if (!str_contains($wp->request, self::API_NAMESPACE)) {
        return;
      }

      if (str_contains($wp->request, ResourceNames::GAME)) {
        $apiController = new GameApiController();
        $apiController->registerRoutes();
      }

      if (str_contains($wp->request, ResourceNames::RACE_NUMBER)) {
        $apiController = new RaceNumberApiController();
        $apiController->registerRoutes();
      }

      if (str_contains($wp->request, ResourceNames::RULE_SET)) {
        $apiController = new RuleSetApiController();
        $apiController->registerRoutes();
      }

      if (str_contains($wp->request, ResourceNames::RULE_SET_RULE)) {
        $apiController = new RuleSetRuleApiController();
        $apiController->registerRoutes();
      }

      if (str_contains($wp->request, ResourceNames::SERVER)) {
        $apiController = new ServerApiController();
        $apiController->registerRoutes();
      }
    }

  }