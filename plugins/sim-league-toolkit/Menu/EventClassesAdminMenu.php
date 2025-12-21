<?php

  namespace SLTK\Menu;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Pages\EventClasses\CreateEventClassAdminPage;
  use SLTK\Pages\EventClasses\EventClassAdminPage;
  use SLTK\Pages\EventClasses\EventClassesAdminPage;

  class EventClassesAdminMenu implements AdminMenu {

    public function init(?string $parentSlug = null): string {
      $pluralPageTitle = esc_html__('Event Classes', 'sim-league-toolkit');
      $singlePageTitle = esc_html__('Event Class', 'sim-league-toolkit');
      $createPageTitle = esc_html__('Create Event Class', 'sim-league-toolkit');

      add_submenu_page(
        $parentSlug,
        $pluralPageTitle,
        $pluralPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::EVENT_CLASSES,
        function () {
          (new EventClassesAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $singlePageTitle,
        $singlePageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::EVENT_CLASS,
        function() {
          (new EventClassAdminPage())->render();
        }
      );

      add_submenu_page(
        '-',
        $createPageTitle,
        $createPageTitle,
        Constants::MANAGE_OPTIONS_PERMISSION,
        AdminPageSlugs::CREATE_EVENT_CLASS,
        function() {
          (new CreateEventClassAdminPage())->render();
        }
      );

      return AdminPageSlugs::EVENT_CLASSES;
    }
  }