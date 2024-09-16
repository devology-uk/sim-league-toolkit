<?php

  namespace SLTK\Domain;

  use SLTK\Database\Repositories\ScoringSetRepository;
  use stdClass;

  class ScoringSet extends DomainBase {
    public string $description = '';
    public string $name = '';
    public int $pointsForFastestLap = 0;
    public int $pointsForFinishing = 0;
    public int $pointsForPole = 0;

    public function __construct(stdClass $data = null) {
      if($data != null) {
        $this->id = $data->id;
        $this->description = $data->description ?? '';
        $this->name = $data->name ?? '';
        $this->pointsForFastestLap = $data->pointsForFastestLap;
        $this->pointsForFinishing = $data->pointsForFinishing;
        $this->pointsForPole = $data->pointsForPole;
      }
    }

    /**
     * @inheritDoc
     */
    public static function get(int $id): ScoringSet|null {
      return ScoringSetRepository::getById($id);
    }

    /**
     * @inheritDoc
     *
     * @return ScoringSet[] Collection of all scoring sets
     */
    public static function list(): array {
      return ScoringSetRepository::list();
    }

    /**
     * @inheritDoc
     */
    public function save(): bool {
      return true;
    }

    public function toTableItem(): array {
      return [
        'id'                  => $this->id,
        'name'                => $this->name,
        'pointsForFastestLap' => $this->pointsForFastestLap,
        'pointsForFinishing'  => $this->pointsForFinishing,
        'pointsForPole'       => $this->pointsForPole,
      ];
    }
  }