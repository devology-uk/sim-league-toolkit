<?php

  namespace SLTK\Components;

  use SLTK\Core\Constants;
  use SLTK\Domain\Member;

  /**
   * A form input for selecting a Member
   */
  class MemberSelectorComponent implements FormFieldComponent {
    private const string FIELD_ID = 'sltk-member-selector';

    private MemberSelectorComponentConfig $config;
    private int $currentValue = Constants::DEFAULT_ID;
    private bool $isDisabled = false;

    /**
     * Creates and instance of the MemberSelectorComponent
     *
     * @param MemberSelectorComponentConfig|null $config Configuration settings for the component
     */
    public function __construct(MemberSelectorComponentConfig $config = null) {
      $this->config = $config ?? new MemberSelectorComponentConfig();
      $postedValue = sanitize_text_field($_POST[self::FIELD_ID] ?? Constants::DEFAULT_ID);

      if($postedValue !== $this->currentValue) {
        $this->currentValue = $postedValue;
      }
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string {
      return $this->currentValue;
    }

    /**
     * @inheritDoc
     */
    public function render(): void {

      $members = Member::list();
      ?>
      <select id='<?= self::FIELD_ID ?>' name='<?= self::FIELD_ID ?>'
        <?php
          disabled($this->isDisabled);
          if($this->config->submitOnSelect) {
            ?>
            onchange='this.form.submit()'
            <?php
          }
        ?>
      >
        <option value='<?= Constants::DEFAULT_ID ?>'><?= esc_html__('Please Select...', 'sim-league-toolkit') ?>.
        </option>
        <?php
          foreach($members as $member) { ?>
            <option value='<?= $member->id ?>' <?= selected($this->currentValue, $member->id, false) ?>><?= $member->displayName ?></option>
            <?php
          }
        ?>
      </select>
      <?php
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $value): void {
      $this->currentValue = $value;
      $this->isDisabled = $this->config->disableOnSetValue;
    }
  }