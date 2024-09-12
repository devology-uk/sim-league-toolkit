<?php

  namespace SLTK\Pages;

  use SLTK\Core\HtmlTagProvider;
  use SLTK\Core\HttpRequestHandler;
  use SLTK\Core\QueryParamNames;

  abstract class ControllerBase extends HttpRequestHandler {
    protected final const string NONCE_ACTION = 'sltk-nonce-action';
    protected final const string NONCE_NAME = 'sltk-nonce';

    /**
     * Creates a new instance of ControllerBase
     */
    public function __construct() {
      if($this->isGetRequest()) {
        $this->handleGet();
      } else {
        $this->handlePost();
      }
    }

    /**
     * @return bool Indicates whether the current visitor is logged in
     */
    public function isLoggedIn(): bool {
      return is_user_logged_in();
    }

    /**
     * @return int The value of the notificationId field from the query of the current url
     */
    protected function getNotificationIdFromUrl(): int {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::NOTIFICATION_ID, -1);
    }

    /**
     * Handles get requests
     *
     * @return void
     */
    protected abstract function handleGet(): void;

    /**
     * Handles post requests
     *
     * @return void
     */
    protected abstract function handlePost(): void;

    /**
     * Renders a hidden field within the current response
     *
     * @param string $id The id/name of the hidden field
     * @param string $value The value of the hidden field
     *
     * @return void
     */
    protected function theHiddenField(string $id, string $value): void {
      HtmlTagProvider::theHiddenField($id, $value);
    }

    /**
     * Outputs a hidden nonce field for form validation
     *
     * @return void
     */
    protected function theNonce(): void {
      wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
    }

    /**
     * Validates whether a nonce is valid and renders an error message if not
     *
     * @return bool Indicates whether the nonce is valid
     */
    protected function validateNonce(): bool {
      $nonceValue = $this->getSanitisedFieldFromPost(self::NONCE_NAME);
      $isValid = wp_verify_nonce($nonceValue, self::NONCE_ACTION);
      if(!$isValid) {
        HtmlTagProvider::theErrorMessage(esc_html__('The request is invalid or you do not have permission to perform this operation', 'sim-league-toolkit'));
      }

      return $isValid;
    }
  }