<?php
  namespace SLTK\Pages\Server\Settings;

  class FallbackServerSettingsProvider extends ServerSettingsProvider{

    public function render(): void {
      ?>
        <p>
          <?= esc_html__('Game specific settings are not supported for this Game/Platform combination at this time.  We are working on them.', 'sim-league-toolkit') ?>
        </p>
      <?php
    }

    public function save(): void {
    }

    public function canSave(): bool {
      return false;
    }
  }