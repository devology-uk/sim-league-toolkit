<?php

  namespace SLTK\Domain;

  use SLTK\Components\GameSelectorComponent;
  use SLTK\Components\PlatformSelectorComponent;
  use SLTK\Core\Constants;
  use SLTK\Database\Repositories\GamesRepository;
  use SLTK\Database\Repositories\PlatformRepository;
  use SLTK\Database\Repositories\ServerRepository;
  use stdClass;

  class Server extends DomainBase {
    public final const string GAME_ID_FIELD_NAME = 'sltk-game-id';
    public final const string GAME_KEY_FIELD_NAME = 'sltk-game-key';
    public final const string IS_HOSTED_FIELD_NAME = 'sltk-server-is-hosted';
    public final const string NAME_FIELD_NAME = 'sltk-server-name';

    public int $gameId = Constants::DEFAULT_ID;
    public bool $isHostedServer = false;
    public string $name = '';
    public int $platformId = 1;
    private array $settings = [];

    public function __construct(stdClass $data = null) {
      if($data) {
        $this->id = $data->id;
        $this->name = $data->name ?? '';
        $this->isHostedServer = $data->isHostedServer ?? false;
        $this->gameId = $data->gameId ?? Constants::DEFAULT_ID;
        $this->platformId = $data->platformId ?? 1;
      }

      if($this->id !== Constants::DEFAULT_ID) {
        $this->settings = ServerRepository::listSettings($this->id);
      }
    }

    public static function get(int $id): Server {
      return ServerRepository::getById($id);
    }

    public static function list(): array {
      return ServerRepository::list();
    }

    public function save(): bool {
      if($this->id === Constants::DEFAULT_ID) {
        $this->id = ServerRepository::add($this);
      }

      return $this->id !== Constants::DEFAULT_ID;
    }

    public function applySetting(string $settingName, string $settingValue): void {
      $this->settings[$settingName] = $settingValue;
    }

    public function getSetting(string $settingName): ?string {
      return $this->settings[$settingName] ?? null;
    }

    /**
     * @return ServerSetting[]
     */
    public function getSettings(): array {
      return $this->settings;
    }

    /**
     * @return array{fieldName: string, value: mixed}
     */
    public function toArray(): array {
      return [
        'name'           => $this->name,
        'isHostedServer' => $this->isHostedServer,
        'gameId'         => $this->gameId,
        'platformId'     => $this->platformId,
      ];
    }

    /**
     * @return array{columnName: string, value: mixed}
     */
    public function toTableItem(): array {
      return [
        'id'             => $this->id,
        'name'           => $this->name,
        'game'           => GamesRepository::getName($this->gameId),
        'isHostedServer' => $this->isHostedServer ? 'Yes' : 'No',
        'platform'       => PlatformRepository::getName($this->platformId)
      ];
    }


    public function validate(): ValidationResult {
      $result = new ValidationResult();

      if ($this->gameId === Constants::DEFAULT_ID) {
        $result->addValidationError(GameSelectorComponent::FIELD_ID,
          esc_html__('You must select a game.', 'sim-league-toolkit'));
      }

      if (empty($this->name)) {
        $result->addValidationError(self::NAME_FIELD_NAME, esc_html__('A unique name is required.', 'sim-league-toolkit'));
      }

      if ($this->platformId === Constants::DEFAULT_ID) {
        $result->addValidationError(PlatformSelectorComponent::FIELD_ID,
          esc_html__('You must select a platform.', 'sim-league-toolkit'));
      }

      return $result;
    }
  }