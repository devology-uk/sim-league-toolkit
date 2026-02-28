<?php

namespace SLTK\Api;

use SLTK\Api\Traits\HasDelete;
use SLTK\Api\Traits\HasGet;
use SLTK\Api\Traits\HasPost;
use SLTK\Core\Constants;
use SLTK\Domain\ChampionshipEntry;
use WP_REST_Request;
use WP_REST_Response;

class ChampionshipEntryApiController extends ApiController {
    use HasDelete, HasGet, HasPost;

    public function __construct() {
        parent::__construct(ResourceNames::CHAMPIONSHIP_ENTRY);
    }

    public function registerRoutes(): void {
        $this->registerDeleteRoute();
        $this->registerRoute(ResourceNames::CHAMPIONSHIP . '/' . Constants::ROUTE_PATTERN_ID . '/entries', 'GET', [$this, 'canGet'], [$this, 'get']);
        $this->registerRoute(ResourceNames::CHAMPIONSHIP . '/' . Constants::ROUTE_PATTERN_ID . '/entries', 'POST', [$this, 'canPost'], [$this, 'post']);
    }

    public function canGet(): bool {
        return true;
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            $data = ChampionshipEntry::listByChampionship($this->getId($request));

            return ApiResponse::success(array_map(fn($e) => $e->toDto(), $data));
        });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            $params = $this->getParams($request);

            $entry = new ChampionshipEntry();
            $entry->setChampionshipId($this->getId($request));
            $entry->setEventClassId((int)$params['eventClassId']);
            $entry->setCarId((int)$params['carId']);
            $entry->setUserId((int)$params['userId']);

            $entry->save();

            return ApiResponse::created($entry->getId());
        });
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            ChampionshipEntry::delete($this->getId($request));

            return ApiResponse::noContent();
        });
    }
}
