<?php

  namespace SLTK\Pages;

  use SLTK\Core\HtmlTagProvider;
  use SLTK\Core\HttpRequestHandler;
  use SLTK\Core\QueryParamNames;

  abstract class ControllerBase extends HttpRequestHandler {
    protected final const string NONCE_ACTION = 'sltk-nonce-action';
    protected final const string NONCE_NAME = 'sltk-nonce';

    /**
     * @var string[]
     */
    protected array $errors = [];

    public function __construct() {
      if($this->isGetRequest()) {
        $this->handleGet();
      } else {
        $this->handlePost();
      }
    }

    public function isLoggedIn(): bool {
      return is_user_logged_in();
    }

    protected function getError(string $key): string {
      return $this->errors[$key] ?? '';
    }

    protected function getNotificationIdFromUrl(): int {
      return $this->getSanitisedFieldFromUrl(QueryParamNames::NOTIFICATION_ID, -1);
    }

    protected abstract function handleGet(): void;

    protected abstract function handlePost(): void;

    protected function theNonce(): void {
      wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
    }

    protected function validateNonce(): bool {
      $nonceValue = $this->getSanitisedFieldFromPost(self::NONCE_NAME);
      $isValid = wp_verify_nonce($nonceValue, self::NONCE_ACTION);
      if(!$isValid) {
        HtmlTagProvider::theErrorMessage(esc_html__('The request is invalid or you do not have permission to perform this operation', 'sim-league-toolkit'));
      }

      return $isValid;
    }
  }