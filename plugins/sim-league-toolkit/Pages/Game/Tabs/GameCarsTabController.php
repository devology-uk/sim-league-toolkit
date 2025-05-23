<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GameCarsTabController extends ControllerBase {

    private Game $game;
    private bool $isReadOnly;

    public function __construct(Game $game, bool $isReadOnly) {
      parent::__construct();
      $this->game = $game;
      $this->isReadOnly = $isReadOnly;
    }

    public function theCars(): void { ?>
        <table class='admin-table'>
            <tr>
                <th>Name</th>
                <th>Class</th>
                <th>Year</th>
                <th>Manufacturer</th>
                <th>Game Key</th>
            </tr>
          <?php
            $cars = $this->game->getCars();
            foreach ($cars as $car) {
              ?>
                <tr>
                    <td><?= $car->getName() ?></td>
                    <td><?= $car->getClassName() ?></td>
                    <td><?= $car->getYear() ?></td>
                    <td><?= $car->getManufacturer() ?></td>
                    <td><?= $car->getCarKey() ?></td>
                </tr>
              <?php
            }
          ?>
        </table>
      <?php
    }

    public function theLeader(): void { ?>
        <p>
          <?= $this->game->getName() ?> <?= esc_html__('includes the following cars', 'sim-league-toolkit') ?>
        </p>
      <?php
    }

    protected function handleGet(): void {

    }

    protected function handlePost(): void {

    }
  }