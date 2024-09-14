<?php

  namespace SLTK\Pages\Migrate;

  use SLTK\Pages\AdminPage;

  class MigrateAdminPage implements AdminPage {

    private MigrateAdminPageController $controller;

    public function __construct() {
      $this->controller = new MigrateAdminPageController();
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
      ?>
      <div class='wrap'>
        <h3><?= esc_html__('Migrate', 'sim-league-toolkit') ?></h3>
        <p><?= esc_html__('If you are using Sim League Toolkit to build a website for an existing league then the tools here will help you migrate your existing data to get up and running quickly.', 'sim-league-toolkit') ?></p>
        <h4><?= esc_html__('Member Import', 'sim-league-toolkit') ?></h4>
        <p>
          <?= esc_html__('Sim League Toolkit extends WordPress user profiles with some information it uses to provide some of the automated features, like generating configuration files for game servers.', 'sim-league-toolkit') ?>
          <?= esc_html__('When creating individual user accounts you can manually provide this information or users can provide it themself when editing their profile.', 'sim-league-toolkit') ?>
          <?= esc_html__('If you have all of this information from an existing league and have or can get that data in a CSV file, this tool allows you to import that file.', 'sim-league-toolkit') ?>
          <?= esc_html__('The data in the file will be used to update existing users or create new users.  Existing users will be matched by email address, which should be unique for each user.', 'sim-league-toolkit') ?>
        </p>
        <h6><?= esc_html__('File Format', 'sim-league-toolkit') ?></h6>
        <p><?= esc_html__('The CSV file must be in a specific format.  The first line must contain the following column names exactly as shown, in the order shown and separated by commas', 'sim-league-toolkit') ?></p>
        <ul>
          <li>Email</li>
          <li>Username</li>
          <li>FirstName</li>
          <li>LastName</li>
          <li>SteamID</li>
          <li>PlayStationID</li>
          <li>XBoxID</li>
          <li>RaceNumber</li>
        </ul>
        <p>
          <?= esc_html__('Each subsequent line must contain the information for a single user. For existing users only Email is required.  For new users both Email and Username are required', 'sim-league-toolkit') ?>
          <?= esc_html__('New users will be sent an email notifying them the account has been created and providing a link for them to sign in and set their password.', 'sim-league-toolkit') ?>
          <?= esc_html__('The following is an example of what the CSV file should look like.  You can download a template based on this example using the button below.', 'sim-league-toolkit') ?>
        </p>
        <pre>
          Email,Username,FirstName,LastName,SteamID,PlayStationID,XBoxID,RaceNumber
          fred@bedrock.com,fredf,Fred,Flintstone,12345678910111213,9876543210123456789,xsomeone,10
          barney@bedrock.com,barneyr,Barney,Rubble,,,,11
          wilmaf@bedrock.com,,,,,,,12
        </pre>
        <p>
          <?= esc_html__('In the above example...', 'sim-league-toolkit') ?>
        </p>
        <p>
          <?= esc_html__('The first data row provides values for all columns.  If no user exists with this email address one will be created and the profile populated with the information from the other columns.  If a user exists with this email address only SteamID, PlayStationID, XBoxID and RaceNumber will be used to update the user profile.', 'sim-league-toolkit') ?>
        </p>
        <p>
          <?= esc_html__('The second data row provides only Email, Username, FirstName, LastName and RaceNumber.  If no user exists with this email address one will be created and the profile populated with the information from the other columns. SteamID, PlayStationId and XBoxID will be empty.  If a user exists with this email address the RaceNumber will be set on the user profile.  FirstName and LastName will be only be set on the existing user profile if they are empty.', 'sim-league-toolkit') ?>
        </p>
        <p>
          <?= esc_html__('The third data row provides only Email and RaceNumber.  If no user exists with this email address the row will be ignored and this will be indicated in an import report on completion.  If a user exists with this email address the RaceNumber will be set on the user profile.', 'sim-league-toolkit') ?>
        </p>
        <p>
          <?= esc_html__('PLEASE NOTE: If a race number is set that is already allocated the existing race number allocation will be set to 0 (Zero).', 'sim-league-toolkit') ?>
        </p>
        <?php
          $this->controller->theTemplateLink()
        ?>
        <form class='sltk-migrate-form' method='post' enctype='multipart/form-data'>
          <?php
            $this->controller->theHiddenFields();
            $this->controller->theFileSelector();
            submit_button(esc_html__('Import', 'sim-league-toolkit'));
            $this->controller->theResults();
          ?>
        </form>

      </div>
      <?php
    }
  }