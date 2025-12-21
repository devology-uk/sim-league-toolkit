<?php

    namespace SLTK\Components;

    use Exception;
    use SLTK\Core\Constants;
    use SLTK\Domain\Car;
    use SLTK\Domain\DriverCategory;

    class DriverCategorySelectorComponent implements FormFieldComponent {
        public final const string FIELD_ID = 'sltk-driver-category-selector';

        private SelectorComponentConfig $config;
        private int $currentValue = Constants::DEFAULT_ID;
        private bool $isDisabled = false;

        public function __construct(SelectorComponentConfig $config = null) {
            $this->config = $config ?? new SelectorComponentConfig(false, false);
            $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

            if ($postedValue !== $this->currentValue) {
                $this->currentValue = $postedValue;
            }
        }

        public function getTooltip(): string {
            return $this->config->toolTip;
        }

        public function getValue(): ?int {
            return max($this->currentValue, Constants::DEFAULT_ID);
        }

        /**
         * @throws Exception
         */
        public function render(): void {

            $driverCategories = DriverCategory::list();
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
                    foreach ($driverCategories as $driverCategory) { ?>
                        <option value='<?= $driverCategory->id ?>' <?= selected($this->currentValue, $driverCategory->id, false) ?>><?= $driverCategory->getName() ?></option>
                        <?php
                    }
                ?>
            </select>
            <?php
        }

        public function setValue(mixed $value): void {
            $this->currentValue = $value;
            $this->isDisabled = $this->config->disableOnSetValue;
        }
    }