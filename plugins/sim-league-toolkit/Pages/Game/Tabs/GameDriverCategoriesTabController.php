<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GameDriverCategoriesTabController extends ControllerBase {

    private Game $game;

    public function __construct(Game $game) {
      parent::__construct();
      $this->game = $game;
    }


    public function theLeader(): void { ?>
      <p>
        <?= $this->game->getName() ?> <?= esc_html__('supports the following driver categories', 'sim-league-toolkit') ?>
      </p>
      <?php
    }

    public function theCategories(): void { ?>
      <table class='admin-table'>
        <tr>
          <th>Name</th>
          <th>Plaque</th>
        </tr>
        <?php
          $classes = $this->game->getDriverCategories();
          foreach ($classes as $class) {
            ?>
            <tr>
              <td><?= $class->getName() ?></td>
              <td><?= $class->getPlaque() ?></td>
            </tr>
            <?php
          }
        ?>
      </table>
      <?php
    }

    protected function handleGet(): void {

    }

    protected function handlePost(): void {

    }
  }