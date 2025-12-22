<?php

    namespace SLTK\Components;

    use Exception;
    use SLTK\Core\Constants;
    use SLTK\Domain\Car;

    class CarClassSelectorComponent extends FormFieldComponent {
        public final const string FIELD_ID = 'sltk-car-class-selector';

        private SelectorComponentConfig $config;
        private string $currentValue = '';
        private int $gameId = Constants::DEFAULT_ID;
        private bool $isDisabled = false;

        public function __construct(?SelectorComponentConfig $config = null) {
            $this->config = $config ?? new SelectorComponentConfig(false, true);
        }

        public function getTooltip(): string {
            return $this->config->toolTip;
        }

        public function getValue(): ?string {
            if ($this->isFormPost()) {
                $this->currentValue = $this->getPostedValue(self::FIELD_ID);
            }

            return !empty($this->currentValue) ? $this->currentValue : '';
        }

        /**
         * @throws Exception
         */
        public function render(): void {

            $carClasses = Car::listClassesForGame($this->gameId);
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
                <option value=''><?= esc_html__('Please Select...', 'sim-league-toolkit') ?></option>
                <?php
                    foreach ($carClasses as $carClass) { ?>
                        <option value='<?= $carClass ?>' <?= selected($this->currentValue, $carClass, false) ?>><?= $carClass ?></option>
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