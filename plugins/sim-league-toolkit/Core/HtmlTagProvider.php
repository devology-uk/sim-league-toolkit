<?php

  namespace SLTK\Core;

  /**
   * Provides methods to output common html tags
   */
  class HtmlTagProvider {

    /**
     * Renders a JavaScript block that returns to the previous page after specified delay
     *
     * @param int $delaySeconds Optional number of seconds to wait before navigating to the previous url
     *
     * @return void
     */
    public static function theBackScript(int $delaySeconds = 0): void {
      if($delaySeconds > 0) {
        $delay = $delaySeconds * 1000;
        ?>
        <script>
          setTimeout(function() {
            history.go(-1);
          }, <?= $delay ?>);
        </script>
        <?php
      } else {
        ?>
        <script>
          history.go(-1);
        </script>
        <?php
      }
    }

    /**
     * Renders an error message in the style of an Admin notice
     *
     * @param string $message The message to be displayed
     * @param bool $canDismiss Indicates whether the message can be dismissed by the user.
     *
     * @return void
     */
    public static function theErrorMessage(string $message, bool $canDismiss = false): void { ?>
      <div class='notice notice-error'>
        <p>
          <?= $message ?>
        </p>
      </div>
      <?php
    }

    /**
     * Renders a JavaScript block that forces navigation to the same page
     *
     * @return void
     */
    public static function theForceNavigationToSamePageScript(): void { ?>
      <script>
        window.location.replace('<?= FieldNames::HTTP_REFERER ?>');
      </script>
      <?php
    }

    /**
     * Renders a hidden field within the current response
     *
     * @param string $id The id/name of the hidden field
     * @param string $value The value of the hidden field
     *
     * @return void
     */
    public static function theHiddenField(string $id, string $value): void { ?>
      <input type='hidden' id='<?= $id ?>' name='<?= $id ?>' value='<?= $value ?>' />
      <?php
    }

    /**
     * Renders an informational message in the style of an Admin notice
     *
     * @param string $message The message to be displayed
     * @param bool $canDismiss Indicates whether the message can be dismissed
     *
     * @return void
     */
    public static function theInfoMessage(string $message, bool $canDismiss = false): void { ?>
      <div class='notice notice-info'>
        <p>
          <?= $message ?>
        </p>
      </div>
      <?php
    }

    /**
     * Renders a script block to redirect the current page to a specified url
     *
     * @param string $url The target url
     * @param int $delaySeconds Number of seconds to wait before navigating
     *
     * @return void
     */
    public static function theRedirectScript(string $url, int $delaySeconds = 0): void {
      if($delaySeconds > 0) {
        $delay = $delaySeconds * 1000;
        ?>
        <script>
          setTimeout(function() {
            window.location.href = '<?= $url ?>';
          }, <?= $delay ?>);
        </script>
        <?php
      } else {
        ?>
        <script>
          window.location.href = '<?= $url ?>';
        </script>
        <?php
      }
    }

    /**
     * Renders a success message in the style of an Admin notice
     *
     * @param string $message The message to be displayed
     * @param bool $canDismiss Indicates whether the message can be dismissed
     *
     * @return void
     */
    public static function theSuccessMessage(string $message, bool $canDismiss = false): void { ?>
      <div class='notice notice-success'>
        <p>
          <?= $message ?>
        </p>
      </div>
      <?php
    }

    /**
     * Renders a warning message in the style of an Admin notice
     *
     * @param string $message The message to be displayed
     * @param bool $canDismiss Indicates whether the message can be dismissed
     *
     * @return void
     */
    public static function theWarningMessage(string $message, bool $canDismiss = false): void { ?>
      <div class='notice notice-warning'>
        <p>
          <?= $message ?>
        </p>
      </div>
      <?php
    }
  }