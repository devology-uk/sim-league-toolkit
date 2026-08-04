<?php

  namespace SLTK\Blocks\Render;

  use WP_Block;

  interface BlockRenderer {
    public function render(array $attributes, string $content, WP_Block $block): string;
  }
