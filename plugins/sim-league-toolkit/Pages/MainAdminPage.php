<?php

namespace SLTK\Pages;

class MainAdminPage implements AdminPage {

    public function render(): void {
        ?>
        <div class='wrap'>
            <h1><?= esc_html(__('Sim League Toolkit', 'sim-league-toolkit')) ?></h1>
            <p><?= esc_html(__('Thank you for installing Sim League Tool KIt', 'sim-league-toolkit')) ?></p>
            <p><?= esc_html(__('Sim League Tool Kit is a WordPress plugin that provides a set of custom Gutenberg blocks for building a website for almost any motor racing simulator or game.  It also includes administration tools for managing every aspect of the league.',
                    'sim-league-toolkit')) ?></p>
            <p><?= esc_html(__('You can use the blocks in this package to add league features to pages of an existing WordPress site or create a site from scratch.  If you are starting from scratch you might consider using our starter theme, which is a fully functional sim league website.',
                    'sim-league-toolkit')) ?></p>
        </div>
        <?php
    }
}