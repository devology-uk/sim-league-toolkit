<?php

  namespace SLTK\Blocks\Render;

  use SLTK\Core\Constants;
  use SLTK\Domain\ResultSummary;
  use WP_Block;

  class MyResultsRenderer implements BlockRenderer {
    public function render(array $attributes, string $content, WP_Block $block): string {
      $userId = get_current_user_id();

      if ($userId <= 0) {
        return $this->placeholder(__('Log in to see your results.', 'sim-league-toolkit'));
      }

      $limit = max(1, (int)($attributes['limit'] ?? 10));

      $results = array_merge(
        ResultSummary::listForChampionshipUser($userId, $limit),
        ResultSummary::listForStandaloneUser($userId, $limit)
      );

      if (empty($results)) {
        $emptyAttributes = get_block_wrapper_attributes(['class' => 'sltk-result-list-empty']);

        return "<p {$emptyAttributes}>" . esc_html__("You don't have any results yet.", 'sim-league-toolkit') . '</p>';
      }

      usort($results, fn($a, $b) => $b->eventDateTime <=> $a->eventDateTime);
      $results = array_slice($results, 0, $limit);

      $rows = array_map(fn($result) => $this->resultRow($result), $results);
      $tableAttributes = get_block_wrapper_attributes(['class' => 'sltk-result-list']);

      return "<table {$tableAttributes}>"
        . '<thead><tr>'
        . '<th>' . esc_html__('Event', 'sim-league-toolkit') . '</th>'
        . '<th>' . esc_html__('Session', 'sim-league-toolkit') . '</th>'
        . '<th>' . esc_html__('Date', 'sim-league-toolkit') . '</th>'
        . '<th>' . esc_html__('Position', 'sim-league-toolkit') . '</th>'
        . '<th>' . esc_html__('Status', 'sim-league-toolkit') . '</th>'
        . '<th>' . esc_html__('Points', 'sim-league-toolkit') . '</th>'
        . '</tr></thead>'
        . '<tbody>' . implode('', $rows) . '</tbody>'
        . '</table>';
    }

    private function resultRow(ResultSummary $result): string {
      $eventLabel = $result->championshipName !== null
        ? $result->championshipName . ' — ' . $result->eventName
        : $result->eventName;

      return '<tr>'
        . '<td>' . esc_html($eventLabel) . '</td>'
        . '<td>' . esc_html($result->sessionName) . '</td>'
        . '<td>' . esc_html($result->eventDateTime->format(Constants::STANDARD_DATE_DISPLAY_FORMAT)) . '</td>'
        . '<td>' . esc_html($result->position !== null ? (string)$result->position : '—') . '</td>'
        . '<td>' . esc_html($result->status->label()) . '</td>'
        . '<td>' . esc_html($result->points !== null ? (string)$result->points : '—') . '</td>'
        . '</tr>';
    }

    private function placeholder(string $message): string {
      return '<p class="sltk-tile-placeholder">' . esc_html($message) . '</p>';
    }
  }
