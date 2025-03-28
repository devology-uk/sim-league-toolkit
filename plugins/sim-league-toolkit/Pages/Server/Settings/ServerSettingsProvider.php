<?php

  namespace SLTK\Pages\Server\Settings;

  use SLTK\Core\HttpRequestHandler;
  use SLTK\Domain\Server;

  abstract class ServerSettingsProvider extends HttpRequestHandler {

    protected Server $server;

    /**
   * @var string[]
   */
    protected array $errors = [];

    public function __construct(Server $server) {
      $this->server = $server;
    }

    public abstract function render(): void;

    public abstract function save(): void;

    protected function getError(string $key): string {
      return $this->errors[$key] ?? '';
    }
  }