<?php

  namespace SLTK\Pages\Server;

  use SLTK\Pages\AdminPage;

  /**
   * Renders a page for adding or editing a Server
   */
  class ServerAdminPage implements AdminPage {
    private ServerAdminPageController $controller;

    /**
     * Creates a new instance of ServerAdminPage
     */
    public function __construct() {
      $this->controller = new ServerAdminPageController();
    }

    /**
     * @inheritDoc
     */
    public function init(): void {
      // TODO: Implement init() method.
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
      ?>
      <div class='wrap'>
        <h2>Server</h2>
        <p>
          <?php
            echo esc_html__('Sim League Toolkit supports multiple games, the server configuration for each game is different.  ', 'sim-league-toolkit');
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