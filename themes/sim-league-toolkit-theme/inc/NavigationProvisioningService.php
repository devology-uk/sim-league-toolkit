<?php

  namespace SLTK\Theme;

  class NavigationProvisioningService {
    private const string MenuTitle = 'Primary Menu';

    public static function provision(): void {
      if (self::navigationMenuExists()) {
        return;
      }

      wp_insert_post([
        'post_title'   => self::MenuTitle,
        'post_status'  => 'publish',
        'post_type'    => 'wp_navigation',
        'post_content' => self::buildNavigationLinks(),
      ]);
    }

    private static function navigationMenuExists(): bool {
      $existingMenus = get_posts([
        'post_type'      => 'wp_navigation',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
      ]);

      return !empty($existingMenus);
    }

    private static function buildNavigationLinks(): string {
      $links = '';

      foreach (PredefinedPages::all() as $page) {
        $wpPage = get_page_by_path($page->slug);

        if (!$wpPage) {
          continue;
        }

        $links .= self::navigationLinkBlock($wpPage, $page->title);
      }

      return $links;
    }

    private static function navigationLinkBlock(\WP_Post $wpPage, string $label): string {
      $attributes = wp_json_encode([
        'label' => $label,
        'type'  => 'page',
        'id'    => $wpPage->ID,
        'url'   => get_permalink($wpPage),
        'kind'  => 'post-type',
      ]);

      return sprintf("<!-- wp:navigation-link %s /-->\n", $attributes);
    }
  }
