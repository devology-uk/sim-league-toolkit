<?php

  namespace SLTK\Blocks\Render;

  use WP_Block;

  class VisibilityRenderer implements BlockRenderer {
    public function render(array $attributes, string $content, WP_Block $block): string {
      $visibleTo = $attributes['visibleTo'] ?? 'everyone';
      $isLoggedIn = is_user_logged_in();

      if ($visibleTo === 'loggedIn' && !$isLoggedIn) {
        return '';
      }

      if ($visibleTo === 'loggedOut' && $isLoggedIn) {
        return '';
      }

      return $content;
    }
  }
