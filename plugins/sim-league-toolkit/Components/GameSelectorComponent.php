<?php

  namespace SLTK\Components;

  use SLTK\Core\Constants;
  use SLTK\Domain\Game;

  class GameSelectorComponent implements FormFieldComponent {
    private const string FIELD_ID = 'sltk-game-selector';

    private GameSelectorComponentConfig $config;
    private int $currentValue = Constants::DEFAULT_ID;
    private bool $isDisabled = false;

    public function __construct(GameSelectorComponentConfig $config = null) {
      $this->config = $config ?? new GameSelectorComponentConfig();
      $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

      if($postedValue !== $this->currentValue) {
        $this->currentValue = $postedValue;
      }
    }

    public function getValue(): string {
      return $this->currentValue;
    }

    public function render(): void {

      $games = Game::list();
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
          foreach($games as $game) { ?>
            <option value='<?= $game->id ?>' <?= selected($this->currentValue, $game->id, false) ?>><?= $game->name ?></option>
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