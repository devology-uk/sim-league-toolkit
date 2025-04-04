<?php

  namespace SLTK\Core;

  class UrlBuilder {

    public static function getAdminPageAbsoluteUrl(string $pageSlug, array $params = []): string {
      $url = get_admin_url() . 'admin.php?page=' . $pageSlug;

      if (count($params) > 0) {
        foreach ($params as $key => $value) {
          $url .= '&' . $key . '=' . $value;
        }
      }

      return $url;
    }

    public static function getAdminPageRelativeUrl(string $pageSlug, array $params = []): string {
      $url = '?page=' . $pageSlug;

      if (count($params) > 0) {
        foreach ($params as $key => $value) {
          $url .= '&' . $key . '=' . $value;
        }
      }

      return $url;
    }

    public static function getFlagIconUrl(string $alpha3Code): string {
      return Constants::PLUGIN_ROOT_URL . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'flags' . DIRECTORY_SEPARATOR . $alpha3Code . '.png';
    }

    public static function getSiteAbsoluteUrl(string $slug, array $params = []): string {
      if (!str_starts_with($slug, '/')) {
        $slug = '/' . $slug;
      }

      $url = home_url($slug);

      if (count($params) > 0) {
        $index = 0;
        foreach ($params as $key => $value) {
          $url .= ($index === 0 ? '?' : '&') . $key . '=' . $value;
          $index++;
        }
      }

      return $url;
    }
  }