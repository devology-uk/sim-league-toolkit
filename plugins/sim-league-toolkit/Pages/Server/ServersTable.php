<?php

  namespace SLTK\Pages\Server;

  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\ListTableBase;
  use SLTK\Domain\Server;

  /**
   * Custom admin table providing the list of servers
   */
  class ServersTable extends ListTableBase {

    public function __construct() {
      parent::__construct('Server',
                          'Servers',
                          AdminPageSlugs::SERVER);
    }

    /**
     * @inheritDoc
     */
    protected function getColumns(): array {
      return [
        'name'           => __('Name', 'sim-league-toolkit'),
        'game'           => __('Game', 'sim-league-toolkit'),
        'platform'       => __('Platform', 'sim-league-toolkit'),
        'isHostedServer' => __('Hosted', 'sim-league-toolkit'),
      ];
    }

    /**
     * @inheritDoc
     */
    protected function getHiddenColumns(): array {
      return [];
    }

    /**
     * @inheritDoc
     *
     * @return Server[]
     */
    protected function getItems(): array {
      $servers = Server::list();

      $results = [];

      foreach($servers as $server) {
        $results[] = $server->toTableItem();
      }

      return $results;
    }

    /**
     * @inheritDoc
     */
    protected function getSortableColumns(): array {
      return array(
        'name'     => ['name', true],
        'game'     => ['game', true],
        'platform' => ['platform', true],
      );
    }
  }