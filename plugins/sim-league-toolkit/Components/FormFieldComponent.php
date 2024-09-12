<?php

  namespace SLTK\Components;
  
  /**
   * Defined members that must be implemented by a component representing a field within a form
   */
  interface FormFieldComponent extends Component {

    /**
     * @return string The value of the component, i.e. the user input
     */
    public function getValue(): string;

    /**
     * @param string $value The initial value of component
     *
     * @return void
     */
    public function setValue(string $value): void;
  }