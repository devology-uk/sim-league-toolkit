<?php

  namespace SLTK\Blocks;

  use SLTK\Blocks\Render\ChampionshipListRenderer;
  use SLTK\Blocks\Render\ChampionshipTileRenderer;
  use SLTK\Blocks\Render\EventListRenderer;
  use SLTK\Blocks\Render\EventTileRenderer;
  use SLTK\Blocks\Render\JoinableItemsRenderer;
  use SLTK\Blocks\Render\LatestResultsRenderer;
  use SLTK\Blocks\Render\MyEventsRenderer;
  use SLTK\Blocks\Render\MyResultsRenderer;
  use SLTK\Blocks\Render\MyTrophiesRenderer;
  use SLTK\Blocks\Render\VisibilityRenderer;

  class BlockManager {
    private const array RENDERERS = [
      'championship-tile' => ChampionshipTileRenderer::class,
      'championship-list' => ChampionshipListRenderer::class,
      'event-tile' => EventTileRenderer::class,
      'event-list' => EventListRenderer::class,
      'visibility' => VisibilityRenderer::class,
      'my-events' => MyEventsRenderer::class,
      'my-results' => MyResultsRenderer::class,
      'my-trophies' => MyTrophiesRenderer::class,
      'latest-results' => LatestResultsRenderer::class,
      'joinable-items' => JoinableItemsRenderer::class,
    ];

    public static function init(): void {
      add_action('init', [self::class, 'registerBlocks']);
      add_filter('block_categories_all', [self::class, 'registerBlockCategory']);
    }

    public static function registerBlocks(): void {
      $blockJsonFiles = glob(SLTK_PLUGIN_DIR . 'build' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'block.json');

      foreach ($blockJsonFiles as $blockJsonFile) {
        $blockDirectory = dirname($blockJsonFile);
        $rendererClass = self::RENDERERS[basename($blockDirectory)] ?? null;

        if ($rendererClass === null) {
          // No PHP renderer registered for this block — it's a static block, rendered
          // entirely from its own saved markup (e.g. sltk/tabs, sltk/tab).
          register_block_type($blockDirectory);
          continue;
        }

        $renderer = new $rendererClass();
        register_block_type($blockDirectory, [
          'render_callback' => [$renderer, 'render'],
        ]);
      }
    }

    public static function registerBlockCategory(array $categories): array {
      return array_merge($categories, [[
        'slug' => 'sltk',
        'title' => __('Sim League Toolkit', 'sim-league-toolkit'),
      ]]);
    }
  }
