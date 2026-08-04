<?php

  namespace SLTK\Blocks\Render;

  use SLTK\Core\Constants;
  use SLTK\Domain\Championship;
  use WP_Block;

  class ChampionshipTileRenderer implements BlockRenderer {
    use RendersTileMarkup;

    public function render(array $attributes, string $content, WP_Block $block): string {
      $championshipId = (int)($attributes['championshipId'] ?? 0);
      $championship = $championshipId > 0 ? Championship::get($championshipId) : null;

      if ($championship === null) {
        return $this->renderPlaceholder(__('Select a championship.', 'sim-league-toolkit'));
      }

      $eventCount = count(Championship::listEvents($championship->getId()));
      $classCount = count(Championship::listClasses($championship->getId()));
      $startDate = $championship->getStartDate()->format(Constants::STANDARD_DATE_DISPLAY_FORMAT);

      $cardBody = '<dl class="sltk-tile-meta">'
        . $this->renderMetaRow(__('Start Date', 'sim-league-toolkit'), $startDate)
        . $this->renderMetaRow(__('Events', 'sim-league-toolkit'), (string)$eventCount)
        . $this->renderMetaRow(__('Classes', 'sim-league-toolkit'), (string)$classCount)
        . '</dl>';

      $dialogBody = ($championship->getDescription() !== ''
          ? '<p class="sltk-tile-description">' . esc_html($championship->getDescription()) . '</p>'
          : '')
        . '<dl class="sltk-tile-meta">'
        . $this->renderMetaRow(__('Start Date', 'sim-league-toolkit'), $startDate)
        . $this->renderMetaRow(__('Game', 'sim-league-toolkit'), $championship->getGame())
        . $this->renderMetaRow(__('Events', 'sim-league-toolkit'), (string)$eventCount)
        . $this->renderMetaRow(__('Classes', 'sim-league-toolkit'), (string)$classCount)
        . '</dl>';

      return $this->renderTileMarkup(
        'championship',
        $championship->getId(),
        $championship->getBannerImageUrl(),
        $championship->getName(),
        $cardBody,
        $dialogBody
      );
    }
  }
