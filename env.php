<?php
// Simple .env loader and env() helper
if (!function_exists('load_dotenv')) {
    function load_dotenv($path = __DIR__ . '/.env') {
        static $loaded = false;
        static $vars = [];
        if ($loaded) return $vars;
        if (!file_exists($path)) {
            $loaded = true;
            return $vars;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (strlen($value) >= 2 && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
                $value = substr($value, 1, -1);
            }
            $vars[$name] = $value;
            if (getenv($name) === false) {
                putenv("$name=$value");
                
                if (!isset($_ENV)) $_ENV = [];
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
        $loaded = true;
        return $vars;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        load_dotenv();
        $val = getenv($key);
        if ($val === false) {
            if (isset($_ENV[$key])) return $_ENV[$key];
            return $default;
        }
        return $val;
    }
}
