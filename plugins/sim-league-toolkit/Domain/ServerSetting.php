<?php

  namespace SLTK\Domain;

  use Exception;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\ServerSettingRepository;
  use stdClass;

  class ServerSetting extends DomainBase {
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

    /**
     * @throws Exception
     */
    public static function delete(int $id): void {
      ServerSettingRepository::delete($id);
    }

    /**
     * @throws Exception
     */
    public static function get(int $id): ServerSetting|null {
      $queryResult = ServerSettingRepository::getById($id);

      if(!$queryResult) {
        return null;

      }
      return new ServerSetting($queryResult);
    }

    /**
     * @throws Exception
     */
    public static function list(): array {
      throw new Exception("Not supported");
    }

    /**
     * @throws Exception
     */
    public static function listByServerId(int $serverId): array {
      $queryResult = ServerSettingRepository::listByServerId($serverId);

      return self::mapServerSettings($queryResult);
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
    public function save(): bool {
      if ($this->hasId()) {
        ServerSettingRepository::update($this->getId(), $this->toArray(false));
      } else {
        $this->setId(ServerSettingRepository::add($this->toArray(false)));
      }

      return true;
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

      if ($includeId && $this->hasId()) {
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

      if ($this->hasId()) {
        $result['id'] = $this->getId();
      }

      return $result;
    }

    private static function mapServerSettings(array $queryResults): array {
      $results = array();

      foreach ($queryResults as $item) {
        $results[] = new ServerSetting($item);
      }

      return $results;
    }
  }