<?php

    namespace SLTK\Components;

    use Exception;
    use SLTK\Core\Constants;
    use SLTK\Domain\Track;

    class TrackSelectorComponent extends FormFieldComponent {
        public final const string FIELD_ID = 'sltk-track-selector';

        private SelectorComponentConfig $config;
        private ?int $currentValue = Constants::DEFAULT_ID;
        private int $gameId = Constants::DEFAULT_ID;
        private bool $isDisabled = false;

        public function __construct(SelectorComponentConfig $config = null) {
            $this->config = $config ?? new SelectorComponentConfig(false, false);
        }

        public function getTooltip(): string {
            return $this->config->toolTip;
        }

        public function getValue(): ?int {
            if ($this->isFormPost()) {
                $this->currentValue = $this->getPostedValue(self::FIELD_ID);
            }

            return $this->currentValue > 0 ? $this->currentValue : null;
        }

        /**
         * @throws Exception
         */
        public function render(): void {

            $tracks = Track::listForGame($this->gameId);
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
                    foreach ($tracks as $track) { ?>
                        <option value='<?= $track->id ?>' <?= selected($this->currentValue, $track->id, false) ?>><?= $track->getShortName() ?></option>
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
            $this->currentValue = $value;
            $this->isDisabled = $this->config->disableOnSetValue;
        }
    }