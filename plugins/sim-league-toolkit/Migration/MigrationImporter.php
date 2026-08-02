<?php

  namespace SLTK\Migration;

  interface MigrationImporter {
    public function getEntityKey(): string;

    public function getLabel(): string;

    public function run(): MigrationRunResult;
  }
