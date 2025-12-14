<?php

  namespace SLTK\Core;

  class BannerImageProvider {
    public static function getRandomBannerImageUrl(): string {
      $imageNumber = rand(1, 7);

      return SLTK_PLUGIN_ROOT_URL . '/images/banner' . $imageNumber . '.png';
    }
  }