<?php

  namespace SLTK\Core;

  class HtmlTagProvider {

    public static function theAdminNumberInput(string $label, string $name, string $value, string $error = '', ?int $minValue = null, ?int $maxValue = null): void {
      ?>
        <tr>
            <th scope='row'>
                <label for='<?= $name ?>' <?= self::errorLabel($error) ?>><?= $label ?></label>
            </th>
            <td>
                <input type='number' id='<?= $name ?>' name='<?= $name ?>'
                       value='<?= $value ?>' <?= isset($minValue) ? "min={$minValue}" : '' ?> <?= isset($maxValue) ? "min={$maxValue}" : '' ?>/>
              <?php
                self::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public static function theAdminTextArea(string $label, string $name, string $value, string $error = '', int $columns = 30, int $rows = 5): void {
      ?>
        <tr>
            <th scope='row' class='va-top'>
                <label for='<?= $name ?>' <?= self::errorLabel($error) ?>><?= $label ?></label>
            </th>
            <td>
                <textarea id='<?= $name ?>' name='<?= $name ?>' cols='<?= $columns ?>' rows='<?= $rows ?>'><?= $value ?></textarea>
              <?php
                self::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public static function theAdminTextInput(string $label, string $name, string $value, string $error = '', string $placeHolder = ''): void {
      ?>
        <tr>
            <th scope='row'>
                <label for='<?= $name ?>' <?= self::errorLabel($error) ?>><?= $label ?></label>
            </th>
            <td>
                <input type='text' id='<?= $name ?>' name='<?= $name ?>' value='<?= $value ?>' placeholder='<?= $placeHolder ?>' />
              <?php
                self::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public static function theBackScript(int $delaySeconds = 0): void {
      if ($delaySeconds > 0) {
        $delay = $delaySeconds * 1000;
        ?>
          <script>
              setTimeout(function () {
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

    public static function theErrorMessage(string $message, bool $canDismiss = false): void { ?>
        <div class='notice notice-error'>
            <p>
              <?= $message ?>
            </p>
        </div>
      <?php
    }

    public static function theForceNavigationToSamePageScript(): void { ?>
        <script>
            window.location.replace('<?= FieldNames::HTTP_REFERER ?>');
        </script>
      <?php
    }

    public static function theHiddenField(string $id, string $value): void { ?>
        <input type='hidden' id='<?= $id ?>' name='<?= $id ?>' value='<?= $value ?>'/>
      <?php
    }

    public static function theInfoMessage(string $message, bool $canDismiss = false): void { ?>
        <div class='notice notice-info'>
            <p>
              <?= $message ?>
            </p>
        </div>
      <?php
    }

    public static function theRedirectScript(string $url, int $delaySeconds = 0): void {
      if ($delaySeconds > 0) {
        $delay = $delaySeconds * 1000;
        ?>
          <script>
              setTimeout(function () {
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

    public static function theSuccessMessage(string $message, bool $canDismiss = false): void { ?>
        <div class='notice notice-success'>
            <p>
              <?= $message ?>
            </p>
        </div>
      <?php
    }

    public static function theWarningMessage(string $message, bool $canDismiss = false): void { ?>
        <div class='notice notice-warning'>
            <p>
              <?= $message ?>
            </p>
        </div>
      <?php
    }

    private static function errorLabel(string $error): string {
      if (!empty($error)) {
        return 'class="sltk-error-label"';
      }

      return '';
    }

    private static function theValidationError(string $error): void {
      if (!empty($error)) {
        ?>
          <div class='sltk-error-text'>
            <?= $error ?>
          </div>
        <?php
      }
    }
  }