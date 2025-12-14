<?php

    namespace SLTK\Components;

    use Exception;
    use SLTK\Core\Constants;
    use SLTK\Domain\Platform;

    class PlatformSelectorComponent implements FormFieldComponent {
        public final const string FIELD_ID = 'sltk-platform-selector';
        private SelectorComponentConfig $config;
        private int $currentValue = Constants::DEFAULT_ID;
        private int $gameId = Constants::DEFAULT_ID;
        private bool $isDisabled = false;

        public function __construct(SelectorComponentConfig $config = null) {
            $this->config = $config ?? new SelectorComponentConfig();
            $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

            if ($postedValue !== $this->currentValue) {
                $this->currentValue = $postedValue;
            }
        }

        public function getValue(): string {
            return $this->currentValue;
        }

        public function getTooltip(): string {
            return $this->config->toolTip;
        }

        /**
         * @throws Exception
         */
        public function render(): void {
            $platforms = Platform::listForGame($this->gameId);

            ?>
            <select id='<?= self::FIELD_ID ?>' name='<?= self::FIELD_ID ?>' title='<?= $this->config->toolTip ?>'
                    <?php
                        disabled($this->isDisabled);
                        if ($this->config->submitOnSelect) {
                            ?>
                            onchange='this.form.submit()'
                            <?php
                        }
                    ?>
            >
                <option value='<?= Constants::DEFAULT_ID ?>'><?= esc_html__('Please Select...', 'sim-league-toolkit') ?></option>
                <?php
                    foreach ($platforms as $platform) { ?>
                        <option value='<?= $platform->id ?>' <?= selected($this->currentValue, $platform->id, false) ?>><?= $platform->getName() ?></option>
                        <?php
                    }
                ?>
            </select>
            <?php
        }

        public function setGameId(int $gameId): void {
            $this->gameId = $gameId;
        }

        public function setValue(mixed $value): void {
            $this->currentValue = (int)$value;
            $this->isDisabled = $this->config->disableOnSetValue;
        }
    }