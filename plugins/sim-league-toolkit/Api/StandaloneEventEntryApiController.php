<?php

namespace SLTK\Api;

use SLTK\Api\Traits\HasDelete;
use SLTK\Api\Traits\HasGet;
use SLTK\Api\Traits\HasPost;
use SLTK\Core\Constants;
use SLTK\Database\Repositories\StandaloneEventEntriesRepository;
use SLTK\Database\Repositories\StandaloneEventsRepository;
use SLTK\Domain\StandaloneEventEntry;
use WP_REST_Request;
use WP_REST_Response;

class StandaloneEventEntryApiController extends ApiController {
    use HasDelete, HasGet, HasPost;

    public function __construct() {
        parent::__construct(ResourceNames::STANDALONE_EVENT_ENTRY);
    }

    public function registerRoutes(): void {
        $this->registerDeleteRoute();
        $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/entries', 'GET', [$this, 'canGet'], [$this, 'get']);
        $this->registerRoute(ResourceNames::STANDALONE_EVENT . '/' . Constants::ROUTE_PATTERN_ID . '/entries', 'POST', [$this, 'canPost'], [$this, 'post']);
    }

    public function canGet(): bool {
        return true;
    }

    protected function onGet(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            $data = StandaloneEventEntry::listByStandaloneEvent($this->getId($request));

            return ApiResponse::success(array_map(fn($e) => $e->toDto(), $data));
        });
    }

    protected function onPost(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            $params = $this->getParams($request);

            $entry = new StandaloneEventEntry();
            $entry->setStandaloneEventId($this->getId($request));
            $entry->setCarId((int)$params['carId']);
            $entry->setUserId((int)$params['userId']);

            if (!empty($params['eventClassId'])) {
                $entry->setEventClassId((int)$params['eventClassId']);
            }

            $entry->save();

            return ApiResponse::created($entry->getId());
        });
    }

    protected function onDelete(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () use ($request) {
            $id = $this->getId($request);
            $entry = StandaloneEventEntry::get($id);

            StandaloneEventEntry::delete($id);

            if ($entry && $entry->getStatus() === 'confirmed' && $entry->getEventClassId() !== null) {
                $maxEntrants = StandaloneEventsRepository::getClassMaxEntrants(
                    $entry->getStandaloneEventId(),
                    $entry->getEventClassId()
                );

                if ($maxEntrants !== null) {
                    StandaloneEventEntriesRepository::promoteFromWaitlist(
                        $entry->getStandaloneEventId(),
                        $entry->getEventClassId(),
                        $maxEntrants
                    );
                }
            }

            return ApiResponse::noContent();
        });
    }
}
