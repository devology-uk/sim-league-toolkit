<?php

  namespace SLTK\Api;

  use SLTK\Domain\RuleSet;

  class ApiRegistrar {

    private static array $routeMap = [
      // More specific patterns first
      '#/' . ResourceNames::CHAMPIONSHIP_EVENT . '#' => ChampionshipEventApiController::class,
      '#/' . ResourceNames::CHAMPIONSHIP . '/\d+/events#' => ChampionshipEventApiController::class,
      '#/' . ResourceNames::CHAMPIONSHIP . '#' => ChampionshipApiController::class,
      '#/' . ResourceNames::DRIVER_CATEGORY . '#' => DriverCategoryApiController::class,
      '#/' . ResourceNames::EVENT_CLASS . '#' => EventClassApiController::class,
      '#/' . ResourceNames::GAME . '#' => GameApiController::class,
      '#/' . ResourceNames::GAME_CONFIG . '#' => GameApiController::class,
      '#/' . ResourceNames::RULE_SET_RULE . '#' => RuleSetRuleApiController::class,
      '#/' . ResourceNames::RULE_SET . '/\d+/rules#' => RuleSetRuleApiController::class,
      '#/' . ResourceNames::RULE_SET . '#' => RuleSetApiController::class,
      '#/' . ResourceNames::SCORING_SET . '#' => ScoringSetApiController::class,
      '#/' . ResourceNames::SERVER_SETTING . '#' => ServerSettingApiController::class,
      '#/' . ResourceNames::SERVER . '/\d+/settings#' => ServerSettingApiController::class,
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