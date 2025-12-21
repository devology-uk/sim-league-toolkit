<?php

  namespace SLTK\Core;

  class HtmlTagProviderInputConfig {

    public function __construct(string $name, string $label, string $value = '', string $error = '', string $placeholder = '', string $type = 'text') {
      $this->name = $name;
      $this->label = $label;
      $this->value = $value;
      $this->type = $type;
      $this->error = $error;
      $this->placeholder = $placeholder;
    }

    public bool $checked = false;
    public bool $disabled = false;
    public string $error = '';
    public string $label = '';
    public ?int $max = null;
    public ?int $min = null;
    public string $name = '';
    public string $placeholder = '';
    public bool $required = false;
    public int $size = 30;
    public ?int $step = null;
    public bool $submitOnChange = false;
    public string $tooltip = '';
    public string $type = 'text';
    public string $value = '';
  }

