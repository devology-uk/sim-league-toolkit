<?php

  namespace SLTK\Pages\EventClasses;

  use Exception;
  use SLTK\Pages\AdminPage;

  class CreateEventClassAdminPage implements AdminPage {
    public function __construct() {
      $this->controller = new CreateEventClassAdminPageController();
    }

    private CreateEventClassAdminPageController $controller;

      /**
       * @throws Exception
       */
      public function render(): void {
      ?>
      <div class='wrap'>
        <h1><?= esc_html__('New Event Class', 'sim-league-toolkit') ?></h1>  <p>
          <?php
            $this->controller->theNewEventClassMessage();
          ?> <form method='post'>
                    <?php
                        $this->controller->theHiddenFields();
                    ?>
                    <table class='form-table'>
                        <?php
                            $this->controller->theGameSelector();
                            $this->controller->theNameField();
                            $this->controller->theDriverCategorySelector();
                            $this->controller->theCarClassSelector();
                            $this->controller->theIsSingleCarClassField();
                            $this->controller->theSingleCarSelector();
                        ?>
                    </table>
              <p>
                  <?php
                      $this->controller->theBackButton();
                      $this->controller->theSaveButton();
                  ?>
              </p>
          </form>


      </div>
      <?php

    }
  }