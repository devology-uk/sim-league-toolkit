<?php

namespace SLTK\Pages\Import;

use SLTK\Core\AdminPageSlugs;
use SLTK\Core\QueryParamNames;
use SLTK\Core\UrlBuilder;
use SLTK\Pages\ControllerBase;

class ImportAdminPageController extends ControllerBase {
    private const string MEMBERS_TAB_NAME = 'members';

    private string $currentTab = '';
    private ImportMembersTab $tab;

    public function theMembersTab(): void { ?>
        <a href="<?= $this->getTabUrl(AdminPageSlugs::IMPORT, self::MEMBERS_TAB_NAME) ?>"
           class="nav-tab <?= $this->getActiveCssClass($this->currentTab, self::MEMBERS_TAB_NAME) ?>"><?= esc_html__('Members',
                'sim-league-toolkit') ?></a>
        <?php
    }

    public function theTabContent(): void {
        $this->tab->render();
    }

    protected function handleGet(): void {
        $this->initialiseState();
    }

    private function initialiseState(): void {
        $this->currentTab = $this->getTabFromUrl();
        $this->prepareTabController();
    }

    private function prepareTabController(): void {
        $this->tab = match ($this->currentTab) {
            default => new ImportMembersTab(),
        };
    }

    protected function handlePost(): void {
        $this->initialiseState();
    }
}