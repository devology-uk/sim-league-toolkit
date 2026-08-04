<?php

  namespace SLTK\Blocks\Patterns;

  class PatternManager {
    private const string CATEGORY = 'sltk';

    public static function init(): void {
      add_action('init', [self::class, 'registerPatterns']);
    }

    public static function registerPatterns(): void {
      register_block_pattern_category(self::CATEGORY, [
        'label' => __('Sim League Toolkit', 'sim-league-toolkit'),
      ]);

      register_block_pattern('sltk/championships-current-and-past', [
        'title' => __('Championships: Current & Recent / Past', 'sim-league-toolkit'),
        'description' => __('A tab strip showing current and recent championships, with a Past tab for completed ones.', 'sim-league-toolkit'),
        'categories' => [self::CATEGORY],
        'content' => CurrentAndPastTabsPattern::forChampionships(),
      ]);

      register_block_pattern('sltk/events-current-and-past', [
        'title' => __('Events: Current & Recent / Past', 'sim-league-toolkit'),
        'description' => __('A tab strip showing current and recent standalone events, with a Past tab for completed ones.', 'sim-league-toolkit'),
        'categories' => [self::CATEGORY],
        'content' => CurrentAndPastTabsPattern::forEvents(),
      ]);
    }
  }
