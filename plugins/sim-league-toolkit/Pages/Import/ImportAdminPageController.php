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
        <a href="<?= $this->getTabUrl(self::MEMBERS_TAB_NAME) ?>"
           class="nav-tab <?= $this->getActiveCssClass(self::MEMBERS_TAB_NAME) ?>"><?= esc_html__('Members',
                'sim-league-toolkit') ?></a>
        <?php
    }

    private function getTabUrl(string $tabName = ''): string {
        $urlParams = [];

        if (!empty($tabName)) {
            $urlParams[QueryParamNames::TAB] = $tabName;
        }

        return UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::IMPORT, $urlParams);
    }

    private function getActiveCssClass(string $tabName = ''): string {
        return $this->currentTab === $tabName ? 'nav-tab-active' : '';
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