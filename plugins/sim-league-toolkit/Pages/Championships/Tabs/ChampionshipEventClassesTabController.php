<?php

  namespace SLTK\Pages\Championships\Tabs;

  use Exception;
  use SLTK\Domain\Championship;
  use SLTK\Pages\ControllerBase;

  class ChampionshipEventClassesTabController extends ControllerBase {

    private ?Championship $championship;

    public function theTable(): void {
      $table = new ChampionshipEventClassesTable($this->championship);
      $table->prepare_items();
      $table->display();
    }

    /**
     * @throws Exception
     */
    private function initialiseState(): void {
      $championshipId = $this->getIdFromUrl();
      $this->championship = Championship::get($championshipId);
    }

    /**
     * @throws Exception
     */
    protected function handleGet(): void {
      $this->initialiseState();
    }

    /**
     * @throws Exception
     */
    protected function handlePost(): void {
      $this->initialiseState();
    }
  }