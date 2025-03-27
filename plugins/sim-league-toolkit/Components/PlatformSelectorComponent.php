<?php

  namespace SLTK\Components;

  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\PlatformRepository;

  class PlatformSelectorComponent implements FormFieldComponent {
    public final const string FIELD_ID = 'sltk-platform-selector';
    private PlatformSelectorComponentConfig $config;
    private int $currentValue = Constants::DEFAULT_ID;
    private bool $isDisabled = false;

    public function __construct(PlatformSelectorComponentConfig $config = null) {
      $this->config = $config ?? new PlatformSelectorComponentConfig();
      $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

      if($postedValue !== $this->currentValue) {
        $this->currentValue = $postedValue;
      }
    }

    public function getValue(): string {
      return $this->currentValue;
    }

    public function render(): void {
      $platforms = PlatformRepository::listAll();

      ?>
      <select id='<?= self::FIELD_ID ?>' name='<?= self::FIELD_ID ?>'
        <?php
          disabled($this->isDisabled);
          if($this->config->submitOnSelect) {
            ?>
            onchange='this.form.submit()'
            <?php
          }
        ?>
      >
        <option value='<?= Constants::DEFAULT_ID ?>'><?= esc_html__('Please Select...', 'sim-league-toolkit') ?>.
        </option>
        <?php
          foreach($platforms as $platform) { ?>
            <option value='<?= $platform->id ?>' <?= selected($this->currentValue, $platform->id, false) ?>><?= $platform->name ?></option>
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