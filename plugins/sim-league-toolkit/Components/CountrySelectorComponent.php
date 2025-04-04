<?php

  namespace SLTK\Components;

  use SLTK\Core\Constants;
  use SLTK\Core\UrlBuilder;
  use SLTK\Domain\Country;

  class CountrySelectorComponent implements FormFieldComponent {
    public final const string FIELD_ID = 'sltk-country-selector';

    private CountrySelectorComponentConfig $config;
    private int $currentValue = Constants::DEFAULT_ID;
    private bool $isDisabled = false;

    public function __construct(GameSelectorComponentConfig $config = null) {
      $this->config = $config ?? new CountrySelectorComponentConfig();
      $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

      if ($postedValue !== $this->currentValue) {
        $this->currentValue = $postedValue;
      }
    }

    public function getValue(): string {
      return $this->currentValue;
    }

    public function render(): void {
      $items = Country::list();
      ?>
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
            <option value='<?= Constants::DEFAULT_ID ?>'><?= esc_html__('Please Select...', 'sim-league-toolkit') ?></option>
          <?php
            foreach ($items as $item) { ?>
                <option value='<?= $item->id ?>' <?= selected($this->currentValue, $item->id, false) ?>><img
                            src='<?= UrlBuilder::getFlagIconUrl($item->alpha3) ?>'
                            alt='<?= esc_html__('Flag of ', 'sim-league-toolkit') . $item->name ?>'/>&nbsp;<?= $item->name ?>
                </option>
              <?php
            }
          ?>
        </select>
      <?php
    }

    public function setValue(string $value): void {
      $this->currentValue = $value;
      $this->isDisabled = $this->config->disableOnSetValue;
    }
  }