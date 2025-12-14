<?php

  namespace SLTK\Core;

  class HtmlTagProvider {

    public static function errorLabelClass(string $error): string {
      if (!empty($error)) {
        return 'class="sltk-error-label"';
      }

      return '';
    }

    public static function theAdminCheckboxInput(string $label, string $name, bool $isChecked, bool $isDisabled = false): void {
      ?>
        <tr>
            <th scope='row'>
                <label class='form-label' for='<?= $name ?>'><?= $label ?></label>
            </th>
            <td>
                <input type='checkbox' id='<?= $name ?>'
                       name='<?= $name ?>' <?= checked($isChecked, true, false) ?>  <?= disabled($isDisabled, true, false) ?> />
            </td>
        </tr>
      <?php
    }

    public static function theAdminInputField(HtmlTagProviderInputConfig $config): void {
      ?>
        <tr>
            <th scope='row'>
                <label class='form-label' for='<?= $config->name ?>' <?= self::errorLabelClass($config->error) ?> title='<?= $config->tooltip ?>'><?= $config->label ?></label>
            </th>
            <td>
                <input type='<?= $config->type ?>'
                       id='<?= $config->name ?>'
                       name='<?= $config->name ?>'
                       value='<?= $config->value ?>'
                       placeholder='<?= $config->placeholder ?>'
                       size='<?= $config->size ?>'
                       title='<?= $config->tooltip ?>'
                  <?= $config->required ? 'required' : '' ?>
                  <?= disabled($config->disabled, true, false) ?>
                  <?= $config->type === 'number' && isset($config->min) ? "min={$config->min}" : '' ?>
                  <?= $config->type === 'number' && isset($config->max) ? "max={$config->max}" : '' ?>
                  <?= $config->type === 'number' && isset($config->step) ? "step={$config->step}" : '' ?>
                />
              <?php
                self::theValidationError($config->error);
              ?>
            </td>
        </tr>
      <?php
    }

    public static function theAdminNumberInput(string $label, string $name, string $value, string $error = '', ?int $minValue = null, ?int $maxValue = null, bool $isDisabled = false): void {
      ?>
        <tr>
            <th scope='row'>
                <label class='form-label' for='<?= $name ?>' <?= self::errorLabelClass($error) ?>><?= $label ?></label>
            </th>
            <td>
                <input type='number' id='<?= $name ?>' name='<?= $name ?>'
                       value='<?= $value ?>' <?= isset($minValue) ? "min={$minValue}" : '' ?> <?= isset($maxValue) ? "max={$maxValue}" : '' ?>
                        <?= disabled($isDisabled, true, false) ?>/>
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
            <th scope='row' class='va-top' style='vertical-align: top'>
                <label class='form-label' for='<?= $name ?>' <?= self::errorLabelClass($error) ?>><?= $label ?></label>
            </th>
            <td>
                <textarea id='<?= $name ?>' name='<?= $name ?>' cols='<?= $columns ?>'
                          rows='<?= $rows ?>'><?= $value ?></textarea>
              <?php
                self::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public static function theAdminTextInput(string $label, string $name, string $value, string $error = '', string $placeHolder = '', int $size = 30, bool $isReadOnly = false): void {
      ?>
        <tr>
            <th scope='row'>
                <label class='form-label' for='<?= $name ?>' <?= self::errorLabelClass($error) ?>><?= $label ?></label>
            </th>
            <td>
                <input type='text' id='<?= $name ?>' name='<?= $name ?>' value='<?= $value ?>'
                       placeholder='<?= $placeHolder ?>' size='<?= $size ?>' <?= $isReadOnly ? 'readonly' : '' ?>/>
              <?php
                self::theValidationError($error);
              ?>
            </td>
        </tr>
      <?php
    }

    public static function theAdminTextDisplay(string $label, string $value): void {
        ?>
        <tr>
            <th scope='row'>
                <label><?= $label ?></label>
            </th>
            <td>
                <span><?= $value?></span>
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
            window.location.replace('<?= CommonFieldNames::HTTP_REFERER ?>');
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

    public static function theValidationError(string $error): void {
      if (!empty($error)) {
        ?>
          <div class='sltk-error-text'>
            <?= $error ?>
          </div>
        <?php
      }
    }

    public static function theWarningMessage(string $message, bool $canDismiss = false): void { ?>
        <div class='notice notice-warning'>
            <p>
              <?= $message ?>
            </p>
        </div>
      <?php
    }
  }