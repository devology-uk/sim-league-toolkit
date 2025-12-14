<?php

    namespace SLTK\Components;

    use SLTK\Core\Constants;
    use SLTK\Domain\Member;

    class MemberSelectorComponent implements FormFieldComponent {
        private const string FIELD_ID = 'sltk-member-selector';

        private SelectorComponentConfig $config;
        private int $currentValue = Constants::DEFAULT_ID;
        private bool $isDisabled = false;

        public function __construct(SelectorComponentConfig $config = null) {
            $this->config = $config ?? new SelectorComponentConfig();
            $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

            if ($postedValue !== $this->currentValue) {
                $this->currentValue = $postedValue;
            }
        }

        public function getTooltip(): string {
            return $this->config->toolTip;
        }

        public function getValue(): string {
            return $this->currentValue;
        }

        public function render(): void {

            $members = Member::list();
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
                <option value='<?= Constants::DEFAULT_ID ?>'><?= esc_html__('Please Select...', 'sim-league-toolkit') ?>
                    .
                </option>
                <?php
                    foreach ($members as $member) { ?>
                        <option value='<?= $member->id ?>' <?= selected($this->currentValue, $member->id, false) ?>><?= $member->displayName ?></option>
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