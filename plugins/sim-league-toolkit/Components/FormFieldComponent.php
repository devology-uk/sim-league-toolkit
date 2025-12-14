<?php

  namespace SLTK\Components;

  interface FormFieldComponent extends Component {

    public function getTooltip(): string;

    public function getValue(): mixed;

    public function setValue(mixed $value): void;
  }