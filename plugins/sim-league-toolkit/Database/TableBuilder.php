<?php

  namespace SLTK\Database;

  interface TableBuilder {
    public function applyAdjustments(string $tablePrefix): void;

    public function definitionSql(string $tablePrefix, string $charsetCollate): string;

    public function initialData(string $tablePrefix): void;

    public function tableName(string $tablePrefix): string;
  }
