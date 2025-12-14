<?php

    namespace SLTK\Core;

    use WP_List_Table;

    abstract class ListTableBase extends WP_List_Table {

        private ?string $createSlugOverride = null;
        private bool $isReadOnly = false;
        private string $singularSlug = '';

        public function __construct(string $singularName, string $pluralName, string $singularSlug, bool $isReadOnly = false, ?string $createSlugOverride = null) {
            parent::__construct([
                    'singular' => $singularName,
                    'plural' => $pluralName,
                    'ajax' => false
            ]);

            $this->singularSlug = $singularSlug;
            $this->isReadOnly = $isReadOnly;
            $this->createSlugOverride = $createSlugOverride;
        }

        public function column_default($item, $column_name): mixed {
            return $item[$column_name];
        }

        /**
         * @return string Content for name column with link to item admin page and actions links
         */
        public function column_name(array $item): string {
            $editParams = [
                    QueryParamNames::ACTION => Constants::ACTION_EDIT,
                    QueryParamNames::ID => $item['id'],
            ];

            $deleteParams = [
                    QueryParamNames::ACTION => Constants::ACTION_DELETE,
                    QueryParamNames::ID => $item['id'],
            ];

            $editUrl = UrlBuilder::getAdminPageRelativeUrl($this->singularSlug, $editParams);
            $deleteUrl = UrlBuilder::getAdminPageRelativeUrl($_REQUEST['page'], $deleteParams);

            $actions = [];

            if ($this->isReadOnly) {
                $actions[Constants::ACTION_EDIT] = '<a href="' . $editUrl . '">' . esc_html__('Open', 'sim-league-toolkit') . '</a>';
            } else {
                $actions[Constants::ACTION_EDIT] = '<a href="' . $editUrl . '">' . esc_html__('Edit', 'sim-league-toolkit') . '</a>';
                $actions[Constants::ACTION_DELETE] = '<a href="' . $deleteUrl . '">' . esc_html__('Delete', 'sim-league-toolkit') . '</a>';
            }

            return "<a href='{$editUrl}'>{$item['name']}</a>{$this->row_actions($actions, false)}";
        }

        public function extra_tablenav($which): void {
            if ($this->isReadOnly) {
                return;
            }

            $params = [
                    QueryParamNames::ACTION => Constants::ACTION_ADD,
            ];

            $createSlug = $this->createSlugOverride ?? $this->singularSlug;
            $url = UrlBuilder::getAdminPageRelativeUrl($createSlug, $params);
            ?>
            <a class='button button-secondary' href='<?= $url ?>'><?= esc_html__('Add', 'sim-league-toolkit') ?></a>
            <?php
        }

        public function get_columns(): array {
            return $this->getColumns();
        }

        public function get_sortable_columns(): array {
            return $this->getSortableColumns();
        }

        public function prepare_items(): void {

            $this->items = $this->getItems();

            $columns = $this->getColumns();
            $hidden = $this->getHiddenColumns();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);
        }

        /**
         * @return array{name: string, title: string}
         */
        protected abstract function getColumns(): array;

        /**
         * @return array{name: string, hidden: bool}
         */
        protected abstract function getHiddenColumns(): array;

        protected abstract function getItems(): array;

        /**
         * @return array{name: string, title: string}
         */
        protected abstract function getSortableColumns(): array;

    }