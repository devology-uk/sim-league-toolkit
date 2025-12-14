<?php

    namespace SLTK\Pages\Championships\Tabs;

    use SLTK\Pages\AdminTab;

    class ChampionshipDetailsTab implements AdminTab {

        private ChampionshipDetailsTabController $controller;

        public function __construct() {
            $this->controller = new ChampionshipDetailsTabController();
        }

        public function render(): void { ?>
            <div class='wrap'>
            <form method='post' enctype='multipart/form-data'>
                <?php
                    $this->controller->theHiddenFields();
                ?>
                <table class='form-table'>
                    <?php
                        $this->controller->theNameField();
                        $this->controller->theDescriptionField();
                        $this->controller->theGameSelector();
                        $this->controller->thePlatformSelector();
                        //            $this->controller->theStartDateField();
                        //            $this->controller->theRuleSetSelector();
                        //            $this->controller->theChampionshipTypeField();
                        //            $this->controller->theTrackMasterTrackSelector();
                        //            $this->controller->theEntryChangeLimitField();
                        //            $this->controller->theResultsToDiscardField();
                    ?>
                </table>

                <p>
                    <?php
                        esc_html__('Championships are displayed with a banner image. By default, one of our built-in banner images will be randomly selected, alternatively you can upload your own PNG image. If you choose to upload an image, it will be resized to 300px wide and 169px high so make sure the image size has similar proportions, or it will be distorted when displayed.', 'sim-league-toolkit');
                    ?>
                </p>

                <?php
                    //          $this->controller->theBannerImageField();
                    //          $this->controller->theBannerImage();
                    $this->controller->theActiveField();
                ?>

                <p>
                    <input type="submit" name="submitFrm" class="button button-primary" value="Save Changes">
                </p>
            </form>
            <?php

        }
    }