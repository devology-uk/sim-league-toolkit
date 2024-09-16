<?php

  namespace SLTK\Api;

  class ApiRegistrar {

    private const string API_NAMESPACE = 'sltk/v1';

    public static function init(): void {
      add_action('rest_api_init', [self::class, 'registerRoutes']);
    }

    public static function registerRoutes(): void {
      global $wp;

      if(!str_contains($wp->request, self::API_NAMESPACE)) {
        return;
      }

      if(str_contains($wp->request, 'race-numbers')) {
        $apiController = new RaceNumbersApiController();
        $apiController->registerRoutes();
      }

      if(str_contains($wp->request, 'server')) {
        $apiController = new ServerApiController();
        $apiController->registerRoutes();
      }
    }

  }