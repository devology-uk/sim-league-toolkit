<?php

  namespace SLTK\Core;

  /**
   * Utility for building urls to pages within the admin app
   */
  class UrlBuilder {

    /**
     * @param string $pageSlug The slug of the target page
     * @param array $params Associative array of url parameters names and values to include
     *
     * @return string Absolute url for the target page with query parameters
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
     * @param string $pageSlug The slug of the target page
     * @param array $params Associative array of url parameter names and values to include
     *
     * @return string Relative url for the target page with query parameters
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
     * @param string $slug The of the target page or view in the website
     * @param array $params Associate array of url parameter names and values to include
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