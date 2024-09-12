<?php

  namespace SLTK\Pages\Server;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Core\QueryParamNames;
  use SLTK\Core\UrlBuilder;
  use SLTK\Domain\Server;
  use WP_List_Table;

  /**
   * Custom admin table providing the list of servers
   */
  class ServersTable extends WP_List_Table {

    /**
     * Instantiates an instance of ServersTableComponent
     */
    public function __construct() {
      parent::__construct(array(
                            'singular' => 'Server',
                            'plural'   => 'Servers',
                            'ajax'     => false
                          ));
    }

    /**
     * Default handler for getting the value for a column name from the underlying data item
     *
     * @param $item mixed The data item for the row being rendered
     * @param $column_name string The name of the column being rendered
     *
     * @return mixed The value for the specified column
     */
    public function column_default($item, $column_name): mixed {
      return $item[$column_name];
    }

    /**
     * Provides output for additional navigation control displayed above and below the table
     *
     * @param $which
     *
     * @return void
     */
    public function extra_tablenav($which): void {
      $params = [
        QueryParamNames::ACTION => Constants::ACTION_ADD,
      ];

      $url = UrlBuilder::getAdminPageRelativeUrl(AdminPageSlugs::SERVER, $params);
      echo sprintf('<a class="button-secondary" href="%s">Add</a>', $url);
    }

    /**
     * @return array Map of column names to headings
     */
    public function get_columns(): array {
      return [
        'name'           => __('Name', 'sim-league-toolkit'),
        'game'           => __('Game', 'sim-league-toolkit'),
        'platform'       => __('Platform', 'sim-league-toolkit'),
        'isHostedServer' => __('Hosted', 'sim-league-toolkit'),
      ];
    }

    /**
     * @return array[] Map of columns that are sortable
     */
    public function get_sortable_columns(): array {
      return array(
        'name'     => ['name', true],
        'game'     => ['game', true],
        'platform' => ['platform', true],
      );
    }

    /**
     * Prepare and populate the $items property with row data
     *
     * @return void
     */
    public function prepare_items(): void {

      $servers = Server::list();

      foreach($servers as $server) {
        $this->items[] = $server->toTableItem();
      }

      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();
      $this->_column_headers = array($columns, $hidden, $sortable);
    }

    /**
     * Custom handler for the name column
     *
     * @param $item array The data item for the current row
     *
     * @return string Content for name column with link to item admin page and actions links
     */
    public function column_name(array $item): string {
      $editParams = [
        QueryParamNames::ACTION => Constants::ACTION_EDIT,
        QueryParamNames::ID     => $item['id'],
      ];

      $deleteParams = [
        QueryParamNames::ACTION => Constants::ACTION_DELETE,
        QueryParamNames::ID     => $item['id'],
      ];

      $editUrl = UrlBuilder::getAdminPageRelativeUrl(AdminPageSlugs::SERVER, $editParams);
      $deleteUrl = UrlBuilder::getAdminPageRelativeUrl($_REQUEST['page'], $deleteParams);

      $actions = array(
        Constants::ACTION_EDIT   => sprintf('<a href="%s">Edit</a>', $editUrl),
        Constants::ACTION_DELETE => sprintf('<a href="%s">Delete</a>', $deleteUrl),
      );

      return sprintf('<a href="%s">%s</a>%s', $editUrl, $item['name'], $this->row_actions($actions, false));
    }
  }