<?php

  namespace SLTK\Pages\Championships\Tabs;

  use Exception;
  use SLTK\Core\BannerImageProvider;
  use SLTK\Core\CommonFieldNames;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Domain\Championship;
  use SLTK\Pages\ControllerBase;

  class ChampionshipBannerImageTabController extends ControllerBase {

    private Championship $championship;

    public function theHiddenFields(): void {
      $this->theNonce();
      HtmlTagProvider::theHiddenField(Championship::CHAMPIONSHIP_ID_FIELD_NAME, $this->championship->id);
      HtmlTagProvider::theHiddenField(Championship::BANNER_IMAGE_URL_HIDDEN_FIELD_NAME, $this->championship->getBannerImageUrl());
    }

    /**
     * @throws Exception
     */
    private function initialiseState(): void {
      $id = $this->getIdFromUrl();
      $this->championship = Championship::get($id);
    }

    /**
     * @throws Exception
     */
    protected function handleGet(): void {
      $this->initialiseState();

      $bannerImageUrl = $this->championship->getBannerImageUrl();
      if(empty($bannerImageUrl)) {
        $this->championship->setBannerImageUrl(BannerImageProvider::getRandomBannerImageUrl());
        $this->championship->save();
      }
    }

    /**
     * @throws Exception
     */
    protected function handlePost(): void {
      if(!$this->validateNonce()) {
        return;
      }

      $this->initialiseState();
      $bannerImageFile = $this->getFile(Championship::BANNER_IMAGE_FILE_FIELD_NAME);
      if(!empty($bannerImageFile['name'])) {
        $upload = wp_handle_upload($bannerImageFile, array('test_form' => false));
        $this->championship->setBannerImageUrl($upload['url']);
        $this->championship->save();
      }
    }

    public function theBannerImage(): void {
      $bannerImageUrl = $this->championship->getBannerImageUrl();
      if(!empty($bannerImageUrl)) { ?>
        <p>
          <img src="<?= $bannerImageUrl ?>" alt="<?= esc_html__('Banner Image', 'sim-league-toolkit') ?>" height="169px" />
        </p>
        <?php
      }
    }

    public function theBannerImageField(): void { ?>
      <p>
        <label class='form-label' for="<?= Championship::BANNER_IMAGE_FILE_FIELD_NAME ?>"><?= esc_html__('Banner Image', 'sim-league-toolkit') ?></label>
        <input type='file' name="<?= Championship::BANNER_IMAGE_FILE_FIELD_NAME ?>"
               id="<?= Championship::BANNER_IMAGE_FILE_FIELD_NAME ?>" accept='image/png' />
      </p>
      <?php
    }


    public function theSaveButton(): void {
      ?>
      <input type='submit' class='button button-primary' id='<?= CommonFieldNames::SAVE_BUTTON ?>'
             name='<?= CommonFieldNames::SAVE_BUTTON ?>' value='<?= esc_html__('Upload', 'sim-league-toolkit') ?>'
             title='<?= esc_html__('Upload the image and save the change to the championship.', 'sim-league-toolkit') ?>'/>
      <?php
    }
  }