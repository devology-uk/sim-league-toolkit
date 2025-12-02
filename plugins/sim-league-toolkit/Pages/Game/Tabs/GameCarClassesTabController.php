<?php

  namespace SLTK\Pages\Game\Tabs;

  use Exception;
  use SLTK\Domain\Game;
  use SLTK\Pages\ControllerBase;

  class GameCarClassesTabController extends ControllerBase {

    private Game $game;

    public function __construct(Game $game, bool $isReadOnly) {
      parent::__construct();
      $this->game = $game;
    }


    public function theLeader(): void { ?>
      <p>
        <?= $this->game->getName() ?> <?= esc_html__('supports the following car classes/categories', 'sim-league-toolkit') ?>
      </p>
      <?php
     }

      /**
       * @throws Exception
       */
      public function theClasses(): void { ?>
       <table class='admin-table'>
         <tr>
             <th>Name</th>
         </tr>
         <?php
           $classes = $this->game->getCarClasses();
           foreach ($classes as $class) {
               ?>
                    <tr>
                        <td><?= $class->getName() ?></td>
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