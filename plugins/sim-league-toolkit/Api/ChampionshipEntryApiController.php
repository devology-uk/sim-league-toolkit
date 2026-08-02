<?php

namespace SLTK\Api;

use SLTK\Api\Traits\HasDelete;
use SLTK\Api\Traits\HasGet;
use SLTK\Api\Traits\HasPost;
use SLTK\Core\Constants;
use SLTK\Database\Repositories\ChampionshipEntriesRepository;
use SLTK\Database\Repositories\ChampionshipRepository;
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
        return current_user_can(Constants::MANAGE_OPTIONS_PERMISSION);
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
            $championshipId = $this->getId($request);
            $eventClassId = (int)$params['eventClassId'];

            $entry = new ChampionshipEntry();
            $entry->setChampionshipId($championshipId);
            $entry->setEventClassId($eventClassId);
            $entry->setCarId((int)$params['carId']);
            $entry->setUserId((int)$params['userId']);
            $entry->setStatus($this->resolveEntryStatus($championshipId, $eventClassId));

            $entry->save();

            return ApiResponse::created($entry->getId());
        });
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            $id = $this->getId($request);
            $entry = ChampionshipEntry::get($id);

            ChampionshipEntry::delete($id);

            if ($entry && $entry->getStatus() === 'confirmed') {
                $classMaxEntrants = ChampionshipRepository::getClassMaxEntrants(
                    $entry->getChampionshipId(),
                    $entry->getEventClassId()
                );

                ChampionshipEntriesRepository::promoteFromWaitlist(
                    $entry->getChampionshipId(),
                    $entry->getEventClassId(),
                    $classMaxEntrants,
                    ChampionshipRepository::getMaxEntrants($entry->getChampionshipId())
                );
            }

            return ApiResponse::noContent();
        });
    }

    private function resolveEntryStatus(int $championshipId, int $eventClassId): string {
        $classMaxEntrants = ChampionshipRepository::getClassMaxEntrants($championshipId, $eventClassId);

        if ($classMaxEntrants !== null && ChampionshipEntriesRepository::getConfirmedCountForClass($championshipId, $eventClassId) >= $classMaxEntrants) {
            return 'waitlisted';
        }

        $championshipMaxEntrants = ChampionshipRepository::getMaxEntrants($championshipId);

        if ($championshipMaxEntrants > 0 && ChampionshipEntriesRepository::getConfirmedCountForChampionship($championshipId) >= $championshipMaxEntrants) {
            return 'waitlisted';
        }

        return 'confirmed';
    }
}
