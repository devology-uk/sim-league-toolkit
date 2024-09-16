<?php

  namespace SLTK\Components;

  interface FormFieldComponent extends Component {

    public function getValue(): string;

    public function setValue(string $value): void;
  }