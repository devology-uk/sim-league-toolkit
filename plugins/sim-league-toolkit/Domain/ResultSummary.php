<?php

  namespace SLTK\Domain;

  use DateTime;
  use Exception;
  use SLTK\Core\Enums\ResultStatus;
  use SLTK\Database\Repositories\ChampionshipSessionResultsRepository;
  use SLTK\Database\Repositories\StandaloneSessionResultsRepository;
  use stdClass;

  final class ResultSummary {
    public function __construct(
      public readonly string $eventType,
      public readonly string $eventName,
      public readonly ?string $championshipName,
      public readonly string $sessionName,
      public readonly DateTime $eventDateTime,
      public readonly string $memberName,
      public readonly ?int $position,
      public readonly ResultStatus $status,
      public readonly ?int $points,
    ) {
    }

    public static function fromStdClass(stdClass $data, string $eventType): self {
      return new self(
        eventType: $eventType,
        eventName: $data->eventName ?? '',
        championshipName: $data->championshipName ?? null,
        sessionName: $data->sessionName ?? '',
        eventDateTime: new DateTime($data->eventDateTime),
        memberName: $data->memberName ?? '',
        position: isset($data->position) ? (int)$data->position : null,
        status: ResultStatus::tryFrom($data->status) ?? ResultStatus::Finished,
        points: isset($data->points) ? (int)$data->points : null,
      );
    }

    /**
     * @return ResultSummary[]
     * @throws Exception
     */
    public static function listForChampionshipUser(int $userId, int $limit): array {
      $results = ChampionshipSessionResultsRepository::listByUserId($userId, $limit);

      return array_map(fn($row) => self::fromStdClass($row, 'championship'), $results);
    }

    /**
     * @return ResultSummary[]
     * @throws Exception
     */
    public static function listForStandaloneUser(int $userId, int $limit): array {
      $results = StandaloneSessionResultsRepository::listByUserId($userId, $limit);

      return array_map(fn($row) => self::fromStdClass($row, 'standalone'), $results);
    }

    /**
     * @return ResultSummary[]
     * @throws Exception
     */
    public static function listRecentChampionship(int $limit): array {
      $results = ChampionshipSessionResultsRepository::listRecent($limit);

      return array_map(fn($row) => self::fromStdClass($row, 'championship'), $results);
    }

    /**
     * @return ResultSummary[]
     * @throws Exception
     */
    public static function listRecentStandalone(int $limit): array {
      $results = StandaloneSessionResultsRepository::listRecent($limit);

      return array_map(fn($row) => self::fromStdClass($row, 'standalone'), $results);
    }
  }
