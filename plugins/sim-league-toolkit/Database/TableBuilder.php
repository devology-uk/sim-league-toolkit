<?php

  namespace SLTK\Database;

  /**
   * Defines the methods that need to be implemented by a type that builds a custom table required by Sim League Toolkit
   */
  interface TableBuilder {
    /**
     * Applies any schema or data adjustments not handled by dbDelta
     *
     * @param string $tablePrefix The table prefix configured for the current WordPress installation
     *
     * @return void
     */
    public function applyAdjustments(string $tablePrefix): void;

    /**
     * Gets the table definition sql to use with dbDelta
     *
     * @param string $tablePrefix The table prefix configured for the current WordPress installation
     * @param string $charsetCollate The character set configured for the current WordPress installation
     *
     * @return string The SQL create statement for the custom table
     */
    public function definitionSql(string $tablePrefix, string $charsetCollate): string;

    /**
     * Adds any static data that needs to be added to the table
     *
     * @param string $tablePrefix The table prefix configured for the current WordPress installation
     *
     * @return void
     */
    public function initialData(string $tablePrefix): void;

    /**
     * Gets the full name of the table the builder is responsible for
     *
     * @param string $tablePrefix The table prefix configured for the current WordPress installation
     *
     * @return string
     */
    public function tableName(string $tablePrefix): string;
  }
