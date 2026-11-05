<?php

/**
 * Loads classes owned by this plugin.
 *
 * @package TaoDock\WordPressAiProvider
 */

declare(strict_types=1);

namespace TaoDock\WordPressAiProvider;

spl_autoload_register(
    static function (string $className): void {
        $namespacePrefix = __NAMESPACE__ . '\\';
        if (strncmp($className, $namespacePrefix, strlen($namespacePrefix)) !== 0) {
            return;
        }

        $relativeClass = substr($className, strlen($namespacePrefix));
        $classFile = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (is_file($classFile)) {
            require $classFile;
        }
    }
);
