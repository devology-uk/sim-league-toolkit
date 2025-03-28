<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ServerRepository;
  use stdClass;

  class ServerSetting {
    public ?int $id;
    public int $serverId = Constants::DEFAULT_ID;
    public string $settingName = '';
    public string $settingValue;

    public function __construct(stdClass $data = null) {
      if($data != null) {
        $this->id = $data->id ?? null;
        $this->serverId = $data->serverId;
        $this->settingName = $data->settingName ?? '';
        $this->settingValue = $data->settingValue ?? '';
      }
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(bool $includeId = true): array {
      $result = [
        'serverId'     => $this->serverId ?? Constants::DEFAULT_ID,
        'settingName'  => $this->settingName ?? '',
        'settingValue' => $this->settingValue ?? '',
      ];

      if($includeId && isset($this->id)) {
        $result['id'] = $this->id;
      }

      return $result;
    }

    public function save(): void {
      if(isset($this->id) && $this->id !== Constants::DEFAULT_ID) {
        ServerRepository::updateSetting($this);
      } else {
        $this->id = ServerRepository::addSetting($this);
      }
    }
  }