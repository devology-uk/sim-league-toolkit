<?php

  namespace SLTK\Blocks\Render;

  trait RendersTileMarkup {
    private function renderTileMarkup(string $idPrefix, int $id, string $bannerImageUrl, string $title, string $cardBodyHtml, string $dialogBodyHtml): string {
      $dialogId = 'sltk-tile-dialog-' . $idPrefix . '-' . $id;
      $tileAttributes = get_block_wrapper_attributes(['class' => 'sltk-tile']);

      ob_start();
      ?>
      <div class="sltk-tile-wrapper">
        <button type="button" <?= $tileAttributes ?> aria-haspopup="dialog" data-dialog-target="<?= esc_attr($dialogId) ?>">
          <?php if ($bannerImageUrl !== '') : ?>
            <img class="sltk-tile-banner" src="<?= esc_url($bannerImageUrl) ?>" alt="" />
          <?php endif; ?>
          <div class="sltk-tile-body">
            <h3 class="sltk-tile-title"><?= esc_html($title) ?></h3>
            <?= $cardBodyHtml ?>
          </div>
        </button>
        <dialog id="<?= esc_attr($dialogId) ?>" class="sltk-tile-dialog">
          <button type="button" class="sltk-dialog-close" aria-label="<?php esc_attr_e('Close', 'sim-league-toolkit'); ?>">&times;</button>
          <?php if ($bannerImageUrl !== '') : ?>
            <img class="sltk-tile-banner" src="<?= esc_url($bannerImageUrl) ?>" alt="" />
          <?php endif; ?>
          <h3 class="sltk-tile-title"><?= esc_html($title) ?></h3>
          <?= $dialogBodyHtml ?>
        </dialog>
      </div>
      <?php
      return ob_get_clean();
    }

    private function renderMetaRow(string $label, string $value): string {
      return sprintf(
        '<div class="sltk-tile-meta-row"><dt>%s</dt><dd>%s</dd></div>',
        esc_html($label),
        esc_html($value)
      );
    }

    private function renderPlaceholder(string $message): string {
      return '<p class="sltk-tile-placeholder">' . esc_html($message) . '</p>';
    }
  }
