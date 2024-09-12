<?php

  namespace SLTK\Pages\Server;

  use SLTK\Pages\AdminPage;

  class ServersAdminPage implements AdminPage {
    /**
     * @inheritDoc
     */
    public function render(): void {
      ?>
      <div id='servers-root'></div>
      <?php
    }
  }