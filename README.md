# goletter/hyperf-observability

Hyperf ECS 日志管道（按日文件、脱敏、链路字段注入）与 OpenTelemetry 链路上下文。

业务「何时打日志、何时开 Span」仍由应用层 Middleware / Listener 实现；本包只提供能力与配置模板。

## 安装

在应用 `composer.json` 中 path autoload（与 hyperf-adv 相同）：

发布配置：

```bash
php bin/hyperf.php vendor:publish goletter/hyperf-observability
```

## 写日志

```php
use Goletter\Observability\Log\Log;

Log::app()->info('order_paid', ['order_id' => 1]);
Log::http()->info('http.access', [...]);
Log::queue()->info('queue_processed', [...]);
```

通道白名单见 `Goletter\Observability\Log\Channels`（http / queue / cli / app / sql）。

链路字段（`trace.id` / `span.id` / `request_id` / …）由 `TraceContextProcessor` 自动注入，无需手动塞进 context。

## 链路上下文

```php
use Goletter\Observability\Trace\Otel\BusinessContext;
use Goletter\Observability\Trace\Otel\OtelBootstrap;
use Goletter\Observability\Trace\Otel\TraceScope;

OtelBootstrap::boot();           // 应用 BootApplication 时调用一次
BusinessContext::fromRequest($request);
$fields = TraceScope::currentTraceFields();
```

HTTP 中间件已内置，在应用 `config/autoload/middlewares.php` 注册即可（建议放在 http 栈最前）：

```php
Goletter\Observability\Middleware\TraceMiddleware::class,
Goletter\Observability\Middleware\AccessLogMiddleware::class,
```

`OtelBootstrapListener` / `TraceBoundaryListener` 等生命周期编排仍由应用侧注册。
