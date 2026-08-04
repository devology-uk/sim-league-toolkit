<?php

  namespace SLTK\Theme;

  class PredefinedPage {
    public function __construct(
      public readonly string $slug,
      public readonly string $title,
      public readonly string $content = ''
    ) {}
  }
