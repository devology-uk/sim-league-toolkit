<?php

    namespace SLTK\Components;

    use SLTK\Core\Constants;
    use SLTK\Domain\RuleSet;

    class RuleSetSelectorComponent implements FormFieldComponent {

        public final const string FIELD_ID = 'sltk-rule-set-selector';
        private SelectorComponentConfig $config;
        private int $currentValue = Constants::DEFAULT_ID;
        private bool $isDisabled = false;

        public function __construct(?SelectorComponentConfig $config = null) {
            $this->config = $config ?? new SelectorComponentConfig();
            $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

            if ($postedValue !== $this->currentValue) {
                $this->currentValue = $postedValue;
            }

        }

        public function getTooltip(): string {
            return $this->config->toolTip;
        }

        public function getValue(): mixed {
            return $this->currentValue;
        }

        public function render(): void {
            $ruleSets = RuleSet::list(); ?>
            <select id='<?= self::FIELD_ID ?>' name='<?= self::FIELD_ID ?>'
                    <?php
                        disabled($this->isDisabled);
                        if ($this->config->submitOnSelect) {
                            ?>
                            onchange='this.form.submit()'
                            <?php
                        }
                    ?>
            >
                <option value='<?= Constants::DEFAULT_ID ?>'><?= esc_html__('None', 'sim-league-toolkit') ?></option>
                <?php
                    foreach ($ruleSets as $ruleSet) { ?>
                        <option value='<?= $ruleSet->id ?>' <?= selected($this->currentValue, $ruleSet->id, false) ?>><?= $ruleSet->getName() ?></option>
                        <?php
                    }
                ?>
            </select>
            <?php
        }

        public function setValue(mixed $value): void {
            $this->currentValue = (int)$value;
            $this->isDisabled = $this->config->disableOnSetValue;
        }
    }