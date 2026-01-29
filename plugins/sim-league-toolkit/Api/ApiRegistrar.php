<?php

  namespace SLTK\Api;

  use SLTK\Domain\RuleSet;

  class ApiRegistrar {

    private static array $routeMap = [
      // More specific patterns first
      '#/' . ResourceNames::CHAMPIONSHIP_EVENT . 's#' => ChampionshipEventApiController::class,
      '#/' . ResourceNames::CHAMPIONSHIP . 's/\d+/events#' => ChampionshipEventApiController::class,
      '#/' . ResourceNames::CHAMPIONSHIP . 's#' => ChampionshipApiController::class,
      '#/' . ResourceNames::DRIVER_CATEGORY . 's#' => DriverCategoryApiController::class,
      '#/' . ResourceNames::EVENT_CLASS . 's#' => EventClassApiController::class,
      '#/' . ResourceNames::GAME . 's#' => GameApiController::class,
      '#/' . ResourceNames::GAME_CONFIG . 's#' => GameApiController::class,
      '#/' . ResourceNames::RACE_NUMBER . 's#' => RaceNumberApiController::class,
      '#/' . ResourceNames::RULE_SET . 's#' => RuleSetApiController::class,
      '#/' . ResourceNames::SCORING_SET . 's#' => ScoringSetApiController::class,
      '#/' . ResourceNames::SERVER . '/\d+/settings#' => ServerSettingApiController::class,
      '#/' . ResourceNames::SERVER_SETTING . '#' => ServerSettingApiController::class,
      '#/' . ResourceNames::SERVER . '#' => ServerApiController::class,
    ];

    public const string API_NAMESPACE = 'sltk/v1';

    private static function resolveController(string $request): ?ApiController {
      foreach (self::$routeMap as $pattern => $controllerClass) {
        if (preg_match($pattern, $request)) {
          return new $controllerClass();
        }
      }

      return null;
    }
    public static function init(): void {
      add_action('rest_api_init', [self::class, 'registerRoutes']);
    }

    public static function registerRoutes(): void {
      global $wp;

      if (!str_contains($wp->request, self::API_NAMESPACE)) {
        return;
      }

      $apiController = self::resolveController($wp->request);
      $apiController?->registerRoutes();
    }

  }