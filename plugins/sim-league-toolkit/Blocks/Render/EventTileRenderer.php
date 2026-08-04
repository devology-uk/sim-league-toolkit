<?php

  namespace SLTK\Blocks\Render;

  use DateTime;
  use SLTK\Core\Constants;
  use SLTK\Domain\StandaloneEvent;
  use WP_Block;

  class EventTileRenderer implements BlockRenderer {
    use RendersTileMarkup;

    public function render(array $attributes, string $content, WP_Block $block): string {
      $eventId = (int)($attributes['eventId'] ?? 0);
      $event = $eventId > 0 ? StandaloneEvent::get($eventId) : null;

      if ($event === null) {
        return $this->renderPlaceholder(__('Select an event.', 'sim-league-toolkit'));
      }

      $eventDate = $event->getEventDate()->format(Constants::STANDARD_DATE_DISPLAY_FORMAT);
      $startTime = $this->formatStartTime($event->getStartTime());

      $cardBody = '<dl class="sltk-tile-meta">'
        . $this->renderMetaRow(__('Track', 'sim-league-toolkit'), $event->getTrack())
        . $this->renderMetaRow(__('Start Date', 'sim-league-toolkit'), $eventDate)
        . $this->renderMetaRow(__('Start Time', 'sim-league-toolkit'), $startTime)
        . '</dl>';

      $dialogBody = ($event->getDescription() !== ''
          ? '<div class="sltk-tile-description">' . wp_kses_post($event->getDescription()) . '</div>'
          : '')
        . '<dl class="sltk-tile-meta">'
        . $this->renderMetaRow(__('Game', 'sim-league-toolkit'), $event->getGame())
        . $this->renderMetaRow(__('Track', 'sim-league-toolkit'), $event->getTrack())
        . $this->renderMetaRow(__('Start Date', 'sim-league-toolkit'), $eventDate)
        . $this->renderMetaRow(__('Start Time', 'sim-league-toolkit'), $startTime)
        . '</dl>';

      return $this->renderTileMarkup(
        'event',
        $event->getId(),
        $event->getBannerImageUrl(),
        $event->getName(),
        $cardBody,
        $dialogBody
      );
    }

    private function formatStartTime(string $rawStartTime): string {
      $parsed = DateTime::createFromFormat(Constants::STANDARD_TIME_FORMAT, $rawStartTime);

      return $parsed !== false ? $parsed->format(Constants::STANDARD_TIME_OF_DAY_FORMAT) : $rawStartTime;
    }
  }
