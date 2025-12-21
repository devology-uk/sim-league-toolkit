<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\Events\CreateIndividualEventAdminPage;
  use SLTK\Pages\Events\EventAdminPage;
  use SLTK\Pages\Events\EventsAdminPage;

  class EventsAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {

      $pluralPageTitle = esc_html__('Individual Events', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Individual Event', 'sim-league-toolkit');
      $addPageTitle = esc_html__('New Individual Event', 'sim-league-toolkit');

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

      add_submenu_page(
        '-',
        $addPageTitle,
        $addPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::CREATE_CHAMPIONSHIP,
        function() {
          (new CreateIndividualEventAdminPage())->render();
        }
      );

      return AdminPageSlugs::EVENTS;
    }
  }
