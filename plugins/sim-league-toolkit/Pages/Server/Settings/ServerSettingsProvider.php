<?php

  namespace SLTK\Pages\Server\Settings;

  use SLTK\Core\HttpRequestHandler;
  use SLTK\Domain\Server;

  /**
   * Members that must be implemented by a game specific settings provider
   */
  abstract class ServerSettingsProvider extends HttpRequestHandler {

    protected Server $server;

    public function __construct(Server $server) {
      $this->server = $server;
    }

    /**
     * Renders the input fields for the game specified settings
     *
     * @return void
     */
    public abstract function render(): void;

    /**
     * Saves the game specific settings
     *
     * @return void
     */
    public abstract function save(): void;
  }