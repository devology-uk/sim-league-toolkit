<?php

  namespace SLTK\Menu;

  interface AdminMenu {
    public function init(string|null $parentSlug = null): string;
  }