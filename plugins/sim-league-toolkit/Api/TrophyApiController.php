<?php

  namespace SLTK\Api;

  use SLTK\Core\Constants;
  use SLTK\Domain\TrophyAwardService;
  use WP_REST_Request;
  use WP_REST_Response;

  class TrophyApiController extends ApiController {

    public function __construct() {
      parent::__construct(ResourceNames::TROPHY);
    }

    public function registerRoutes(): void {
      $this->registerRoute('/' . ResourceNames::STANDALONE_EVENT . '/(?P<id>\d+)/trophies/preview', 'GET', [$this, 'canPreview'], [$this, 'previewStandaloneEventTrophies']);
      $this->registerRoute('/' . ResourceNames::STANDALONE_EVENT . '/(?P<id>\d+)/trophies/award', 'POST', [$this, 'canAward'], [$this, 'awardStandaloneEventTrophies']);
      $this->registerRoute('/' . ResourceNames::CHAMPIONSHIP_EVENT . '/(?P<id>\d+)/trophies/preview', 'GET', [$this, 'canPreview'], [$this, 'previewChampionshipEventTrophies']);
      $this->registerRoute('/' . ResourceNames::CHAMPIONSHIP_EVENT . '/(?P<id>\d+)/trophies/award', 'POST', [$this, 'canAward'], [$this, 'awardChampionshipEventTrophies']);
      $this->registerRoute('/' . ResourceNames::CHAMPIONSHIP . '/(?P<id>\d+)/trophies/preview', 'GET', [$this, 'canPreview'], [$this, 'previewChampionshipTrophies']);
      $this->registerRoute('/' . ResourceNames::CHAMPIONSHIP . '/(?P<id>\d+)/trophies/award', 'POST', [$this, 'canAward'], [$this, 'awardChampionshipTrophies']);
    }

    public function canPreview(): bool {
      return true;
    }

    public function canAward(): bool {
      return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
    }

    public function previewStandaloneEventTrophies(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $preview = TrophyAwardService::previewForStandaloneEvent($this->getId($request));

        return ApiResponse::success($preview->toDto());
      });
    }

    public function awardStandaloneEventTrophies(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $trophies = TrophyAwardService::awardForStandaloneEvent($this->getId($request));

        return ApiResponse::success(array_map(fn($t) => $t->toDto(), $trophies));
      });
    }

    public function previewChampionshipEventTrophies(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $preview = TrophyAwardService::previewForChampionshipEvent($this->getId($request));

        return ApiResponse::success($preview->toDto());
      });
    }

    public function awardChampionshipEventTrophies(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $trophies = TrophyAwardService::awardForChampionshipEvent($this->getId($request));

        return ApiResponse::success(array_map(fn($t) => $t->toDto(), $trophies));
      });
    }

    public function previewChampionshipTrophies(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $preview = TrophyAwardService::previewForChampionship($this->getId($request));

        return ApiResponse::success($preview->toDto());
      });
    }

    public function awardChampionshipTrophies(WP_REST_Request $request): WP_REST_Response {
      return $this->execute(function () use ($request) {
        $trophies = TrophyAwardService::awardForChampionship($this->getId($request));

        return ApiResponse::success(array_map(fn($t) => $t->toDto(), $trophies));
      });
    }
  }
