<?php

    namespace SLTK\Pages;

    use SLTK\Core\Constants;
    use SLTK\Core\HtmlTagProvider;
    use SLTK\Core\HttpRequestHandler;
    use SLTK\Core\QueryParamNames;
    use SLTK\Core\UrlBuilder;

    abstract class ControllerBase extends HttpRequestHandler {
        protected final const string NONCE_ACTION = 'sltk-nonce-action';
        protected final const string NONCE_NAME = 'sltk-nonce';

        public function __construct() {
            if ($this->isGetRequest()) {
                $this->handleGet();
            } else {
                $this->handlePost();
            }
        }

        public function isLoggedIn(): bool {
            return is_user_logged_in();
        }

        /**
         * @var string[]
         */
        protected array $errors = [];

        protected function getActiveCssClass(string $currentTab, string $compareToTabName = ''): string {
            return $currentTab === $compareToTabName ? 'nav-tab-active' : '';
        }

        protected function getError(string $key): string {
            return $this->errors[$key] ?? '';
        }

        protected function getNotificationIdFromUrl(): int {
            return $this->getSanitisedFieldFromUrl(QueryParamNames::NOTIFICATION_ID, -1);
        }

        protected function getTabUrl(string $pageSlug, string $tabName = '', int $id = Constants::DEFAULT_ID): string {
            $urlParams = [];

            if ($id !== Constants::DEFAULT_ID) {
                $urlParams[QueryParamNames::ID] = $id;
            }

            if (!empty($tabName)) {
                $urlParams[QueryParamNames::TAB] = $tabName;
            }

            return UrlBuilder::getAdminPageAbsoluteUrl($pageSlug, $urlParams);
        }

        protected abstract function handleGet(): void;

        protected abstract function handlePost(): void;

        protected function theNonce(): void {
            wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);
        }

        protected function validateNonce(): bool {
            $nonceValue = $this->getSanitisedFieldFromPost(self::NONCE_NAME);
            $isValid = wp_verify_nonce($nonceValue, self::NONCE_ACTION);
            if (!$isValid) {
                HtmlTagProvider::theErrorMessage(esc_html__('The request is invalid or you do not have permission to perform this operation', 'sim-league-toolkit'));
            }

            return $isValid;
        }

        protected function theErrorMessage(string $message, bool $canDismiss = false): void { ?>
            <div class='notice notice-error'>
                <p>
                    <?= $message ?>
                </p>
            </div>
            <?php
        }

        protected function theInfoMessage(string $message, bool $canDismiss = false): void { ?>
            <div class='notice notice-info'>
                <p>
                    <?= $message ?>
                </p>
            </div>
            <?php
        }

        protected function theSuccessMessage(string $message, bool $canDismiss = false): void { ?>
            <div class='notice notice-success'>
                <p>
                    <?= $message ?>
                </p>
            </div>
            <?php
        }

        protected function theWarningMessage(string $message, bool $canDismiss = false): void { ?>
            <div class='notice notice-warning'>
                <p>
                    <?= $message ?>
                </p>
            </div>
            <?php
        }

        protected function theRedirectScript(string $url, int $delaySeconds = 0): void {
            if ($delaySeconds > 0) {
                $delay = $delaySeconds * 1000;
                ?>
                <script>
                    setTimeout(function () {
                        window.location.href = '<?= $url ?>';
                    }, <?= $delay ?>);
                </script>
                <?php
            } else {
                ?>
                <script>
                    window.location.href = '<?= $url ?>';
                </script>
                <?php
            }
        }
    }