<?php

  namespace SLTK\Api;

  use WP_Error;

  /**
   * Base class for api controllers
   */
  abstract class ApiController {
    protected const string NAMESPACE = 'sltk/v1';

    /**
     * Checks the current user permissions are valid
     *
     * @return bool|WP_Error True if valid, otherwise an error
     */
    public function checkPermission(): bool|WP_Error {
      if(!$this->canExecute()) {
        return new WP_Error('rest_forbidden', esc_html__('You do not have permission to access this resource.', 'sim-league-toolkit'), array('status' => $this->getAuthorisationStatusCode()));
      }

      return true;
    }

    /**
     * Register routes handled by the api controller
     * @return void
     */
    public abstract function registerRoutes(): void;

    /**
     * @return bool Indicates whether the user can execute api controller
     */
    protected abstract function canExecute(): bool;

    /**
     * @return int Not found status code based on user auth status
     */
    protected function getAuthorisationStatusCode(): int {

      $status = 401;

      if(is_user_logged_in()) {
        $status = 403;
      }

      return $status;
    }
  }