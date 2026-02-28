<?php

namespace SLTK\Api;

use SLTK\Domain\Member;
use WP_REST_Request;
use WP_REST_Response;

class MemberApiController extends ApiController {

    public function __construct() {
        parent::__construct(ResourceNames::MEMBER);
    }

    public function registerRoutes(): void {
        $this->registerRoute('members', 'GET', [$this, 'canGet'], [$this, 'list']);
    }

    public function canGet(): bool {
        return true;
    }

    public function list(WP_REST_Request $request): WP_REST_Response {
        return $this->execute(function () {
            $data = Member::list();

            return ApiResponse::success(array_values(array_map(fn($m) => $m->toDto(), $data)));
        });
    }
}
