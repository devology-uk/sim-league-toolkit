<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  /**
   * Represents a setting for a game server
   */
  class ServerSetting {
    public ?int $id;
    public int $serverId = Constants::DEFAULT_ID;
    public string $settingName = '';
    public string $settingValue;

    /**
     * Creates a new instance of ServerSetting
     */
    public function __construct(stdClass $data = null) {
      if($data != null) {
        $this->id = $data->id ?? null;
        $this->serverId = $data->serverId;
        $this->settingName = $data->settingName ?? '';
        $this->settingValue = $data->settingValue ?? '';
      }
    }

    public function toArray(): array {
      $result = [
        'serverId'     => $this->serverId ?? Constants::DEFAULT_ID,
        'settingName'  => $this->settingName ?? '',
        'settingValue' => $this->settingValue ?? '',
      ];

      if(isset($this->id)) {
        $result['id'] = $this->id;
      }

      return $result;
    }
  }