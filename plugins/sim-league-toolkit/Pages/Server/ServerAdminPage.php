<?php

  namespace SLTK\Pages\Server;

  use SLTK\Pages\AdminPage;

  class ServerAdminPage implements AdminPage {
    private ServerAdminPageController $controller;

    public function __construct() {
      $this->controller = new ServerAdminPageController();
    }

    public function init(): void {}

    public function render(): void {
      ?>
      <div class='wrap'>
        <h1>Server</h1>
        <p>
          <?php
            echo esc_html__('Sim League Toolkit supports multiple games, the server configuration for each game is different.  ',
                            'sim-league-toolkit');
            $this->controller->theNewServerMessage();
            $this->controller->theExistingServerMessage();
          ?>
        </p>
        <form method='post'>
          <?php
            $this->controller->theServerHiddenFields();
          ?>
          <table class='form-table'>
            <?php
              $this->controller->theGameSelector();
              $this->controller->thePlatformSelector();
              $this->controller->theNameField();
              $this->controller->theIsHostedField();
            ?>
          </table>
          <p>
            <?php
              $this->controller->theSaveServerButton();
              $this->controller->theBackButton();
            ?>
          </p>
        </form>
        <?php
          if($this->controller->showSettings()) {
            ?>
            <hr />
            <section>
              <h3>Game Specific Settings</h3>
              <form method='post'>
                <?php
                  $this->controller->theSettingsHiddenFields();
                  $this->controller->theGameSpecificSettings();
                ?>
                <p>
                  <?php
                    $this->controller->theSaveServerSettingsButton();
                    $this->controller->theBackButton();
                  ?>
                </p>
              </form>
            </section>
            <?php
          }
        ?>
      </div>
      <?php
    }
  }