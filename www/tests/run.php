<?php

/*
 * Тести лендінгу без фреймворку: php tests/run.php (у docker: docker compose exec -T php php /var/www/landing/tests/run.php).
 * Кожен файл tests/*Test.php повертає [назва => fn() => ...]; перевірки — check($умова, $опис).
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/I18n/helpers.php';

$failures = 0;
$total = 0;

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function same(mixed $expected, mixed $actual, string $message = ''): void
{
    check($expected === $actual, ($message ? "{$message}: " : '') . 'очікувалось ' . var_export($expected, true) . ', отримано ' . var_export($actual, true));
}

foreach (glob(__DIR__ . '/*Test.php') as $file) {
    foreach (require $file as $name => $test) {
        $total++;
        try {
            $test();
            echo "  ✓ {$name}\n";
        } catch (Throwable $e) {
            $failures++;
            echo "  ✗ {$name}\n      {$e->getMessage()}\n";
        }
    }
}

echo "\n" . ($failures ? "ПРОВАЛЕНО: {$failures} з {$total}" : "Усі {$total} тестів пройшли") . "\n";
exit($failures ? 1 : 0);
