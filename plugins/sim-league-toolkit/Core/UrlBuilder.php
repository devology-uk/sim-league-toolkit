<?php

  namespace SLTK\Core;

  class UrlBuilder {

    /**
     * @param string $pageSlug
     * @param array $params {name: string, value: mixed}
     *
     * @return string
     */
    public static function getAdminPageAbsoluteUrl(string $pageSlug, array $params = []): string {
      $url = get_admin_url() . 'admin.php?page=' . $pageSlug;

      if(count($params) > 0) {
        foreach($params as $key => $value) {
          $url .= '&' . $key . '=' . $value;
        }
      }

      return $url;
    }

    /**
     * @param string $pageSlug
     * @param array $params {name: string, value: mixed}
     *
     * @return string
     */
    public static function getAdminPageRelativeUrl(string $pageSlug, array $params = []): string {
      $url = '?page=' . $pageSlug;

      if(count($params) > 0) {
        foreach($params as $key => $value) {
          $url .= '&' . $key . '=' . $value;
        }
      }

      return $url;
    }

    /**
     * @param string $slug
     * @param array $params {name: string, value: mixed}
     *
     * @return string Absolute url to a page or view in the website
     */
    public static function getSiteAbsoluteUrl(string $slug, array $params = []): string {
      if(!str_starts_with($slug, '/')) {
        $slug = '/' . $slug;
      }

      $url = home_url($slug);

      if(count($params) > 0) {
        $index = 0;
        foreach($params as $key => $value) {
          $url .= ($index === 0 ? '?' : '&') . $key . '=' . $value;
          $index++;
        }
      }

      return $url;
    }
  }