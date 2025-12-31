<?php

  namespace SLTK\Api;

  use WP_Error;

  abstract class ApiController {
    protected const string NAMESPACE = 'sltk/v1';

    private string $resourceName;

    public function __construct(string $resourceName) {
      $this->resourceName = $resourceName;
    }

    public function checkPermission(): bool|WP_Error {
      if (!$this->canExecute()) {
        return new WP_Error('rest_forbidden', esc_html__('You do not have permission to access this resource.', 'sim-league-toolkit'), array('status' => $this->getAuthorisationStatusCode()));
      }

      return true;
    }

    public abstract function registerRoutes(): void;

    protected abstract function canExecute(): bool;

    protected function getAuthorisationStatusCode(): int {

      $status = 401;

      if (is_user_logged_in()) {
        $status = 403;
      }

      return $status;
    }

    protected function getResourceName(): string {
      return '/' . $this->resourceName;
    }
  }