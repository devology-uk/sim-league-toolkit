<?php

  namespace SLTK\Pages\Rules;


  use SLTK\Core\AdminPageSlugs;
  use SLTK\Core\Constants;
  use SLTK\Core\HtmlTagProvider;
  use SLTK\Core\QueryParamNames;
  use SLTK\Core\UrlBuilder;
  use SLTK\Domain\RuleSet;
  use SLTK\Domain\RuleSetRule;
  use SLTK\Pages\ControllerBase;

  class RuleSetAdminPageController extends ControllerBase {
    private const string SAVE_DETAILS_BUTTON_NAME = 'sltk_save_details';
    private const string SAVE_RULE_BUTTON_NAME = 'sltk_save_rule';
    private int $id = Constants::DEFAULT_ID;
    private ?RuleSet $ruleSet;
    private ?RuleSetRule $ruleSetRule;

    public function showRules(): bool {
      return $this->id !== Constants::DEFAULT_ID;
    }

    public function theBackButton(): void { ?>
        <p>
            <a href="<?= get_admin_url() . 'admin.php?page=' . AdminPageSlugs::RULE_SETS ?>"
               class='button button-secondary'><?= esc_html__('Back to Rule Sets', 'sim-league-toolkit') ?></a>
        </p>
      <?php
    }

    public function theFocusPositionFieldScript(): void { ?>
        <script>
            document.getElementById('<?= RuleSetRule::RULE_FIELD_NAME ?>').focus();
        </script>
      <?php
    }

    public function theHiddenFields(): void {
      $this->theNonce();
    }

    public function theInstructions(): void {
      if ($this->id === Constants::DEFAULT_ID) {
        ?>
          <p>
            <?= esc_html__('Complete the form below and click save to create a new Rule Set.  You will then be able to continue building the Rule Set below.',
              'sim-league-toolkit') ?>
          </p>
        <?php
      } else {
        ?>
          <p>
            <?= esc_html__('The Rule Set is displayed below.  It is divided into 2 sections.  The details at the top can be changed and saved separately from the Rules, which you manage below.',
              'sim-league-toolkit') ?>
          </p>
        <?php
      }
    }

    public function theNameField(): void {
      HtmlTagProvider::theAdminTextInput(esc_html__('Name', 'sim-league-toolkit'),
        RuleSet::NAME_FIELD_NAME,
        $this->ruleSet->getName(),
        $this->getError(RuleSet::NAME_FIELD_NAME));
    }

    public function theRuleEditor(): void {
      HtmlTagProvider::theAdminTextArea(esc_html__('Rule Text', 'sim-league-toolkit'),
        RuleSetRule::RULE_FIELD_NAME,
        $this->ruleSetRule->getRule(),
        $this->getError(RuleSetRule::RULE_FIELD_NAME), 100, 10);
    }

    public function theRuleIndexField(): void {

      HtmlTagProvider::theAdminTextInput(esc_html__('Rule Index', 'sim-league-toolkit'),
        RuleSetRule::RULE_INDEX_FIELD_NAME,
        $this->ruleSetRule->getRuleIndex(),
        $this->getError(RuleSetRule::RULE_INDEX_FIELD_NAME),
        'e.g. 1, 1.1. 1.1.1');
    }

    public function theRuleSetIdField(): void {
      HtmlTagProvider::theHiddenField(RuleSetRule::RULE_SET_ID_FIELD_NAME, $this->ruleSet->id);
    }

    public function theRules(): void {
      $rules = $this->ruleSet->getRules();
      if (count($rules) < 1) {
        ?>
          <p><?= esc_html__('No Rules have been defined for this Rule Set.', 'sim-league-toolkit') ?></p>
        <?php
        return;
      }

      ?>
        <table class='admin-table'>
            <thead>
            <tr>
                <th><?= esc_html__('Index', 'sim-league-toolkit') ?></th>
                <th><?= esc_html__('Rule', 'sim-league-toolkit') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
              $editQueryParams = [
                QueryParamNames::ID => $this->ruleSet->id,
                QueryParamNames::ACTION => Constants::ACTION_EDIT,
              ];
              $deleteRuleQueryParams = [
                QueryParamNames::ID => $this->ruleSet->id,
                QueryParamNames::ACTION => Constants::ACTION_DELETE,
              ];

              foreach ($rules as $rule) {
                $editQueryParams[QueryParamNames::RULE_ID] = $rule->id;
                $deleteRuleQueryParams[QueryParamNames::RULE_ID] = $rule->id;
                ?>
                  <tr>
                      <td class='va-top'><?= $rule->getRuleIndex() ?></td>
                      <td><?= $rule->getRule() ?></td>
                      <td>
                          <a href='<?= UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::RULE_SET, $editQueryParams) ?>'><i
                                      class='dashicons dashicons-edit'></i></a>
                          <a href='<?= UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::RULE_SET, $deleteRuleQueryParams) ?>'><i
                                      class='dashicons dashicons-trash'></i></a>
                      </td>
                  </tr>
                <?php
              }
            ?>
            </tbody>
        </table>
      <?php
    }

    public function theSaveDetailsButton(): void { ?>
        <input type='submit' class='button button-primary' name='<?= self::SAVE_DETAILS_BUTTON_NAME ?>'
               value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'/>
      <?php
    }

    public function theSaveRuleButton(): void { ?>
        <input type='submit' class='button-primary' name='<?= self::SAVE_RULE_BUTTON_NAME ?>'
               value='<?= esc_html__('Save', 'sim-league-toolkit') ?>'/>
      <?php
    }

    public function theTypeSelector(): void { ?>
        <tr>
            <th scope='row'>
                <label for='<?= RuleSet::TYPE_FIELD_NAME ?>'><?= esc_html__('Type', 'sim-league-toolkit') ?></label>
            </th>
            <td>
                <select id='<?= RuleSet::TYPE_FIELD_NAME ?>' name='<?= RuleSet::TYPE_FIELD_NAME ?>'>
                    <option value=''><?= esc_html__('Please select...', 'sim-league-toolkit') ?></option>
                    <option value='<?= RuleSet::TYPE_CHAMPIONSHIP ?>' <?= selected($this->ruleSet->getType(), RuleSet::TYPE_CHAMPIONSHIP, false) ?>><?= esc_html__('Championship', 'sim-league-toolkit') ?></option>
                    <option value='<?= RuleSet::TYPE_EVENT ?>' <?= selected($this->ruleSet->getType(), RuleSet::TYPE_EVENT, false) ?>><?= esc_html__('Event', 'sim-league-toolkit') ?></option>
                </select>
            </td>
        </tr>
      <?php
    }

    private function deleteRule(): void {
      if ($this->ruleSet->deleteRule($this->ruleSetRule->id)) {
        HtmlTagProvider::theSuccessMessage(esc_html__('The rule was deleted successfully, please wait...',
          'sim-league-toolkit'));
        $queryParams = [
          QueryParamNames::ID => $this->ruleSet->id,
          QueryParamNames::ACTION => Constants::ACTION_EDIT
        ];

        $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::RULE_SET, $queryParams);
        HtmlTagProvider::theRedirectScript($url, 1);
      }
    }

    private function handleSaveDetails(): void {
      $this->ruleSet->setName($this->getSanitisedFieldFromPost(RuleSet::NAME_FIELD_NAME, ''));
      $this->ruleSet->setType($this->getSanitisedFieldFromPost(RuleSet::TYPE_FIELD_NAME, ''));

      $validationResult = $this->ruleSet->validate();
      if (!$validationResult->success) {
        $this->errors = $validationResult->validationErrors;

        return;
      }

      if ($this->ruleSet->save()) {
        HtmlTagProvider::theSuccessMessage(esc_html__('The rule set was saved successfully, preparing rules please wait...',
          'sim-league-toolkit'));
        $queryParams = [
          QueryParamNames::ID => $this->ruleSet->id,
          QueryParamNames::ACTION => Constants::ACTION_EDIT
        ];

        $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::RULE_SET, $queryParams);
        HtmlTagProvider::theRedirectScript($url, 2);
      }
    }

    private function handleSaveRule(): void {
      $ruleContent = $this->getSanitisedFieldFromPost(RuleSetRule::RULE_FIELD_NAME, '');
      $ruleIndex = $this->getSanitisedFieldFromPost(RuleSetRule::RULE_INDEX_FIELD_NAME, '');


      $this->ruleSetRule->setRuleSetId($this->id);
      $this->ruleSetRule->setRuleIndex($ruleIndex);
      $this->ruleSetRule->setRule($ruleContent);

      $validationResult = $this->ruleSetRule->validate();
      if (!$validationResult->success) {
        $this->errors = $validationResult->validationErrors;

        return;
      }

      if ($this->ruleSet->saveRule($this->ruleSetRule)) {
        HtmlTagProvider::theSuccessMessage(esc_html__('The rule was saved successfully.', 'sim-league-toolkit'));
        $queryParams = [
          QueryParamNames::ID => $this->ruleSet->id,
          QueryParamNames::ACTION => Constants::ACTION_EDIT
        ];

        $url = UrlBuilder::getAdminPageAbsoluteUrl(AdminPageSlugs::RULE_SET, $queryParams);
        HtmlTagProvider::theRedirectScript($url, 1);
      }
    }

    private function initialiseRule(): void {
      $ruleSetRuleId = $this->getSanitisedFieldFromUrl(QueryParamNames::RULE_ID, '');
      if (empty($ruleSetRuleId)) {
        $this->ruleSetRule = new RuleSetRule();
      } else {
        $this->ruleSetRule = RuleSet::getRuleById($ruleSetRuleId);
      }
    }

    private function initialiseRuleSet(): void {
      $action = $this->getActionFromUrl();
      if ($action !== Constants::ACTION_ADD) {
        $this->id = $this->getIdFromUrl();
        $this->ruleSet = RuleSet::get($this->id);
      } else {
        $this->ruleSet = new RuleSet();
      }
    }

    protected function handleGet(): void {
      $this->initialiseRuleSet();
      $this->initialiseRule();

      if ($this->getActionFromUrl() === Constants::ACTION_DELETE) {
        $this->deleteRule();
      }
    }

    protected function handlePost(): void {
      if (!$this->validateNonce()) {
        return;
      }

      $this->initialiseRuleSet();
      $this->initialiseRule();

      if ($this->postContainsField(self::SAVE_DETAILS_BUTTON_NAME)) {
        $this->handleSaveDetails();
      } else {
        $this->handleSaveRule();
      }
    }
  }
