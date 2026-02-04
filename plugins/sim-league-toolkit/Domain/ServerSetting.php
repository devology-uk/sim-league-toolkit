<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use SLTK\Domain\Abstractions\ValueObject;
  use SLTK\Domain\Traits\HasIdentity;
  use stdClass;

  class ServerSetting implements ValueObject {
    use HasIdentity;

    private int $serverId = Constants::DEFAULT_ID;
    private string $settingName = '';
    private string $settingValue;

    public static function fromStdClass(?stdClass $data): ?self {
      $result = new ServerSetting();

      $result->setId($data->id);
      $result->setServerId($data->serverId);
      $result->setSettingName($data->settingName);
      $result->setSettingValue($data->settingValue);

      return $result;
    }

    public function getServerId(): int {
      return $this->serverId ?? Constants::DEFAULT_ID;
    }

    public function setServerId(int $value): void {
      $this->serverId = $value;
    }

    public function getSettingName(): string {
      return $this->settingName ?? '';
    }

    public function setSettingName(string $value): void {
      $this->settingName = $value;
    }

    public function getSettingValue(): string {
      return $this->settingValue ?? '';
    }

    public function setSettingValue(string $value): void {
      $this->settingValue = $value;
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'serverId' => $this->getServerId(),
        'settingName' => $this->getSettingName(),
        'settingValue' => $this->getSettingValue(),
      ];
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toDto(): array {
      return [
        'id' => $this->getId(),
        'serverId' => $this->getServerId(),
        'settingName' => $this->getSettingName(),
        'settingValue' => $this->getSettingValue(),
      ];
    }
  }