<?php

  namespace SLTK\Theme;

  class PageProvisioningService {
    public static function provision(): void {
      foreach (PredefinedPages::all() as $page) {
        self::createPageIfMissing($page);
      }

      self::setHomeAsFrontPage();
    }

    private static function createPageIfMissing(PredefinedPage $page): void {
      if (get_page_by_path($page->slug)) {
        return;
      }

      wp_insert_post([
        'post_title'   => $page->title,
        'post_name'    => $page->slug,
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
      ]);
    }

    private static function setHomeAsFrontPage(): void {
      $homePage = get_page_by_path(PredefinedPages::homeSlug());

      if (!$homePage) {
        return;
      }

      update_option('show_on_front', 'page');
      update_option('page_on_front', $homePage->ID);
    }
  }
