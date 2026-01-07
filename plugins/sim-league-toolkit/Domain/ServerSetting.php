<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ServerRepository;
  use stdClass;

  class ServerSetting extends EntityBase {
    private int $serverId = Constants::DEFAULT_ID;
    private string $settingName = '';
    private string $settingValue;

    public function __construct(?stdClass $data = null) {
      parent::__construct($data);
      if ($data) {
        $this->serverId = $data->serverId;
        $this->settingName = $data->settingName ?? '';
        $this->settingValue = $data->settingValue ?? '';
      }
    }

    public function getServerId() {
      return $this->serverId ?? Constants::DEFAULT_ID;
    }

    public function setServerId(int $value): void {
      $this->serverId = $value;
    }

    public function getSettingName() {
      return $this->settingName ?? '';
    }

    public function setSettingName(string $value): void {
      $this->settingName = $value;
    }

    public function getSettingValue() {
      return $this->settingValue ?? '';
    }

    public function setSettingValue(string $value): void {
      $this->settingValue = $value;
    }

    /**
     * @throws Exception
     */
    public function save(): void {
      if ($this->getId() !== Constants::DEFAULT_ID) {
        ServerRepository::updateSetting($this->getId(), $this->toArray(false));
      } else {
        $this->setId(ServerRepository::addSetting($this->toArray(false)));
      }
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(bool $includeId = true): array {
      $result = [
        'serverId' => $this->getServerId(),
        'settingName' => $this->getSettingName(),
        'settingValue' => $this->getSettingValue(),
      ];

      if ($includeId  && $this->hasId()) {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toDto(): array {

      $result = [
        'serverId' => $this->getServerId(),
        'settingName' => $this->getSettingName(),
        'settingValue' => $this->getSettingValue(),
      ];

      if($this->hasId()) {
        $result['id'] = $this->getId();
      }

      return $result;
    }
  }