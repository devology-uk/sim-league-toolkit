<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Events\EventAdminPage;
  use SLTK\Pages\Events\EventsAdminPage;

  class EventsAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {

      $pluralPageTitle = esc_html__('Events', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Event', 'sim-league-toolkit');

      add_submenu_page(
        $parentSlug,
        $pluralPageTitle,
        $pluralPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::EVENTS,
        function() {
          (new EventsAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::EVENT,
        function() {
          (new EventAdminPage())->render();
        }
      );

      return AdminPageSlugs::EVENTS;
    }
  }
