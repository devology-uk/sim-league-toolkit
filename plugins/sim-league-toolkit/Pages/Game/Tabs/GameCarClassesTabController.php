<?php

  namespace SLTK\Pages\Game\Tabs;

  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GameCarClassesTabController extends ControllerBase {

    private Game $game;
    private bool $isReadOnly;

    public function __construct(Game $game, bool $isReadOnly) {
      parent::__construct();
      $this->game = $game;
      $this->isReadOnly = $isReadOnly;
    }


    public function theLeader(): void { ?>
      <p>
        <?= $this->game->getName() ?> <?= esc_html__('supports the following car classes/categories', 'sim-league-toolkit') ?>
      </p>
      <?php
     }

     public function theClasses(): void { ?>
       <table class='admin-table'>
         <tr>
             <th>Name</th>
             <th>Display Name</th>
         </tr>
         <?php
           $classes = $this->game->getCarClasses();
           foreach ($classes as $class) {
               ?>
                    <tr>
                        <td><?= $class->getName() ?></td>
                        <td><?= $class->getDisplayName() ?></td>
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