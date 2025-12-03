<?php

  namespace SLTK\Pages\Game\Tabs;


  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GameTracksTabController extends ControllerBase {
    private Game $game;

    public function __construct(Game $game) {
      parent::__construct();
      $this->game = $game;
    }

    public function theLeader(): void { ?>
      <p>
        <?= $this->game->getName() ?> <?= esc_html__('includes the following tracks', 'sim-league-toolkit') ?>
      </p>
      <?php
    }

    public function theTracks(): void {
      ?>
        <table class='admin-table'>
            <tr>
              <th>Short Name</th>
              <th>Full Name</th>
              <th>Country</th>
              <th>Country Code</th>
              <th>Latitude</th>
              <th>Longitude</th>
            </tr>
            <?php
              $tracks = $this->game->getTracks();
              foreach ($tracks as $track) {
                  ?>
                    <tr>
                        <td><?= $track->getShortName() ?></td>
                        <td><?= $track->getFullName() ?></td>
                        <td><?= $track->getCountry() ?></td>
                        <td><?= $track->getCountryCode() ?></td>
                        <td><?= $track->getLatitude() ?></td>
                        <td><?= $track->getLongitude() ?></td>
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