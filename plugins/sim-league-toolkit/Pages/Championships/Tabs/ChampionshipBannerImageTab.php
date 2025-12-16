<?php

  namespace SLTK\Pages\Championships\Tabs;

  use SLTK\Pages\AdminTab;

  class ChampionshipBannerImageTab implements AdminTab {
    private ChampionshipBannerImageTabController $controller;

    public function __construct() {
      $this->controller = new ChampionshipBannerImageTabController();
    }

    public function render(): void {
      ?>
      <div class='wrap'>
        <form method='post' enctype='multipart/form-data'>
          <?php
            $this->controller->theHiddenFields();
          ?>
          <p class='mw-900'>
            <?= esc_html__(' Championships are displayed with a banner image. When you created the championship, one of our built-in banner images was
            randomly selected. You can upload your own PNG image here. If you choose to upload an image, it will
            be resized to 300px wide and 169px high (16:9 ratio) so make sure the image size has similar proportions, or it will be
            distorted when displayed.', 'sim-league-toolkit') ?>
          </p>
          <?php
            $this->controller->theBannerImageField();
            $this->controller->theBannerImage();
            $this->controller->theSaveButton();
          ?>
        </form>
      <?php

    }
  }