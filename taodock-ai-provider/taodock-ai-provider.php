<?php

/**
 * Plugin Name:       TaoDock AI – 40+ AI Models (GPT, Claude, Gemini)
 * Plugin URI:        https://github.com/taodock-api/taodock-wordpress
 * Description:       Connects the WordPress AI Client to TaoDock text models.
 * Version:           1.0.0
 * Requires at least: 7.0
 * Requires PHP:      8.0
 * Author:            TaoDock
 * Author URI:        https://taodock.com
 * License:           GPL-2.0-or-later
 * License URI:       https://spdx.org/licenses/GPL-2.0-or-later.html
 * Text Domain:       taodock-ai-provider
 *
 * @package TaoDock\WordPressAiProvider
 */

declare(strict_types=1);

namespace TaoDock\WordPressAiProvider;

use TaoDock\WordPressAiProvider\Provider\TaoDockProvider;
use WordPress\AiClient\AiClient;

if (!defined('ABSPATH')) {
    return;
}

const VERSION = '1.0.0';

require_once __DIR__ . '/src/Autoloader.php';

/**
 * Adds TaoDock to the provider registry used by the WordPress AI Client.
 */
function register_provider(): void
{
    if (!class_exists(AiClient::class)) {
        return;
    }

    $registry = AiClient::defaultRegistry();
    if (!$registry->hasProvider(TaoDockProvider::class)) {
        $registry->registerProvider(TaoDockProvider::class);
    }
}

add_action('init', __NAMESPACE__ . '\\register_provider', 5);
