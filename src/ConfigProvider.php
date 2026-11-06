<?php

declare(strict_types=1);

namespace Goletter\Observability;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'logger',
                    'description' => 'ECS 日志通道（DailyFileHandler + processors）。',
                    'source' => __DIR__ . '/../publish/logger.php',
                    'destination' => BASE_PATH . '/config/autoload/logger.php',
                ],
                [
                    'id' => 'trace',
                    'description' => '链路追踪 / 访问日志 / 脱敏 / SQL 日志开关。',
                    'source' => __DIR__ . '/../publish/trace.php',
                    'destination' => BASE_PATH . '/config/autoload/trace.php',
                ],
            ],
        ];
    }
}
