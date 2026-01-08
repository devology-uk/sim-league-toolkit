<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;
  use stdClass;

  class TrackLayout extends EntityBase {
    private int $corners;
    private string $game;
    private int $gameId;
    private string $layoutId;
    private int $length;
    private string $name;
    private string $track;
    private int $trackId;

    public function __construct(?stdClass $data = null) {
      parent::__construct($data);

      if ($data) {
        $this->gameId = $data->gameId;
        $this->game = $data->game;
        $this->trackId = $data->trackId;
        $this->track = $data->track;
        $this->layoutId = $data->layoutId;
        $this->name = $data->name;
        $this->corners = $data->corners;
        $this->length = $data->length;
      }
    }

    public function getCorners(): int {
      return $this->corners ?? 0;
    }

    public function getGame(): string {
      return $this->game ?? '';
    }

    public function getGameId(): int {
      return $this->gameId ?? Constants::DEFAULT_ID;
    }

    public function getLayoutId(): string {
      return $this->layoutId;
    }

    public function getLength(): int {
      return $this->length ?? 0;
    }

    public function getName(): string {
      return $this->name ?? '';
    }

    public function getTrack(): string {
      return $this->track ?? '';
    }

    public function getTrackId(): int {
      return $this->trackId ?? Constants::DEFAULT_ID;
    }

    public function toDto(): array {
      return [
        'corners' => $this->corners,
        'game' => $this->game,
        'gameId' => $this->gameId,
        'layoutId' => $this->layoutId,
        'length' => $this->length,
        'name' => $this->name,
        'track' => $this->track,
        'trackId' => $this->trackId,
      ];
    }
  }