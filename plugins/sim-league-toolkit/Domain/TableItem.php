<?php

  namespace SLTK\Domain;

  interface TableItem {
    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toTableItem(): array;
  }