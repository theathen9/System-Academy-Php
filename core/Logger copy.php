<?php

class Logger
{
    private string $path;
    private int $maxSize = 5242880; // 5MB
    private int $retentionDays = 7;

    public function __construct($path = null)
    {
        $this->path = $path ?? __DIR__ . '/../storage/logs/';

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }

        $this->cleanup();
    }

    // =========================
    // 🧠 TRACE ID (REQUEST TRACKING)
    // =========================
    private function traceId(): string
    {
        if (!isset($_SERVER['TRACE_ID'])) {
            $_SERVER['TRACE_ID'] = bin2hex(random_bytes(8));
        }

        return $_SERVER['TRACE_ID'];
    }

    // =========================
    // 📦 BASE CONTEXT (AUTO META)
    // =========================
    private function context(): array
    {
        return [
            'trace_id' => $this->traceId(),
            'ip'       => $_SERVER['REMOTE_ADDR'] ?? null,
            'url'      => $_SERVER['REQUEST_URI'] ?? null,
            'method'   => $_SERVER['REQUEST_METHOD'] ?? null,
            'memory'   => round(memory_get_usage() / 1024 / 1024, 2) . 'MB',
        ];
    }

    // =========================
    // 🧠 WRITE LOG (CORE ENGINE)
    // =========================
    private function write(string $level, string $message, array $context = [])
    {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');

        $file = "{$this->path}app-{$date}.log";

        // rotate if file too big
        if (file_exists($file) && filesize($file) >= $this->maxSize) {
            rename($file, "{$this->path}app-{$date}-" . time() . ".log");
        }

        $log = [
            'time'       => $time,
            'level'      => $level,
            'message'    => $message,
            'fingerprint'=> md5($level . $message),
            'context'    => array_merge($this->context(), $context)
        ];

        file_put_contents(
            $file,
            json_encode($log, JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    // =========================
    // 📊 LOG LEVELS
    // =========================
    public function info($msg, $ctx = [])     { $this->write('INFO', $msg, $ctx); }
    public function warning($msg, $ctx = [])  { $this->write('WARNING', $msg, $ctx); }
    public function error($msg, $ctx = [])    { $this->write('ERROR', $msg, $ctx); }
    public function debug($msg, $ctx = [])    { $this->write('DEBUG', $msg, $ctx); }
    public function security($msg, $ctx = []) { $this->write('SECURITY', $msg, $ctx); }
    public function cache($msg, $ctx = [])    { $this->write('CACHE', $msg, $ctx); }
    public function sql($query, $params = []) { $this->write('SQL', $query, $params); }
    public function critical($msg, $ctx = []) { $this->write('CRITICAL', $msg, $ctx); }

    // =========================
    // 🧹 CLEANUP OLD LOGS
    // =========================
    private function cleanup(): void
    {
        foreach (glob($this->path . '*.log') as $file) {
            if (time() - filemtime($file) > ($this->retentionDays * 86400)) {
                unlink($file);
            }
        }
    }

    // =========================
    // 📖 READ LOG FILE
    // =========================
    public function read(?string $date = null): array
    {
        $date = $date ?? date('Y-m-d');
        $file = "{$this->path}app-{$date}.log";

        if (!file_exists($file)) return [];

        return file($file, FILE_IGNORE_NEW_LINES);
    }

    // =========================
    // 🔍 FILTER BY LEVEL
    // =========================
    public function filter(string $level, ?string $date = null): array
    {
        return array_values(array_filter(
            $this->read($date),
            fn($line) => str_contains($line, "[{$level}]")
        ));
    }

    // =========================
    // 📊 GROUPED ERROR ANALYTICS
    // =========================
    public function groupedErrors(?string $date = null): array
    {
        $logs = $this->read($date);
        $errors = [];

        foreach ($logs as $log) {
            if (str_contains($log, '"ERROR"') || str_contains($log, '"CRITICAL"')) {

                $key = md5($log);

                $errors[$key]['message'] = $log;
                $errors[$key]['count'] = ($errors[$key]['count'] ?? 0) + 1;
            }
        }

        return $errors;
    }
}