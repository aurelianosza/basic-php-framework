<?php

use Core\Config;

function dot_get(array $array, string $path, $default = null) {
    $keys = explode('.', $path);

    foreach ($keys as $key) {
        if (is_array($array) && array_key_exists($key, $array)) {
            $array = $array[$key];
        } else {
            return $default;
        }
    }

    return $array;
}

function dot_set(array &$array, string $path, mixed $value) {
    $keys = explode('.', $path);
    $temporary = &$array;

    foreach ($keys as $segment) {
        if (
            !isset($temporary[$segment]) ||
            !is_array($temporary[$segment])
        ) {
            $temporary[$segment] = [];
        }
        $temporary = &$temporary[$segment];
    }

    $temporary = $value;
}

function pick(array $array, array $paths): array {
    $dataToReturn = [];

    foreach ($paths as $path) {
        $value = dot_get($array, $path, null);
        if ($value !== null) {
            dot_set($dataToReturn, $path, $value);
        }
    }

    return $dataToReturn;
}

function kebab_case(string $value): string
{
    $value = preg_replace('/[_\s]+/', '_', $value);

    $value = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $value));

    $value = preg_replace('/-+/', '_', $value);

    return trim($value, '-');
}

function display(...$vars) {
     if (PHP_SAPI === 'cli') {
        foreach ($vars as $var) {
            if (PHP_SAPI === 'cli') {
                fwrite(STDOUT, print_r($var, true) . PHP_EOL);
            }
        }
    } else {
        echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:8px;'>";
        foreach ($vars as $v) {
            var_dump($v);
            echo "\n";
        }
        echo "</pre>";
    }
}

function dump(...$vars)
{
    display($vars);
    exit;
}

function pretty_fatal_error(): void {
    $error = error_get_last();

    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);

        echo "<pre style='background:#111;color:#f55;padding:15px;border-radius:8px;font-family:monospace'>";
        echo "<b>Fatal Error</b>\n";
        echo "----------------------\n";
        echo "Message : {$error['message']}\n";
        echo "File    : {$error['file']}\n";
        echo "Line    : {$error['line']}\n";
        echo "----------------------\n";
        echo "</pre>";
    }
}

function array_entries(array $arr): array {
    $out = [];
    foreach ($arr as $k => $v) {
        $out[] = [$k, $v];
    }
    return $out;
}

function config(string $path, mixed $default = null): mixed {
    return Config::getInstance()
        ->getConfig($path) ?? $default;
}

function mini_yaml_parse(string $yaml): array
{
    $lines = preg_split('/\r\n|\r|\n/', $yaml);
    $result = [];
    $stack = [&$result]; // pilha de referências
    $indentStack = [0];  // controle de níveis de indentação

    $totalLines = count($lines);

    foreach ($lines as $i => $line) {
        $line = rtrim($line);

        // ignorar linhas vazias ou comentários
        if ($line === '' || str_starts_with(trim($line), '#')) {
            continue;
        }

        // calcular nível de indentação
        preg_match('/^(\s*)/', $line, $m);
        $indent = strlen($m[1]);
        $trimmed = trim($line);

        if (strpos($trimmed, ':') !== false) {
            [$key, $value] = array_map('trim', explode(':', $trimmed, 2));

            // normaliza valor
            if ($value === '') {
                // olha próxima linha para decidir
                $nextLine = $lines[$i + 1] ?? '';
                preg_match('/^(\s*)/', $nextLine, $n);
                $nextIndent = strlen($n[1]);

                if ($nextLine !== '' && $nextIndent > $indent) {
                    $value = []; // tem filhos, será array
                } else {
                    $value = null; // sem filhos, é nulo
                }
            } elseif (is_numeric($value)) {
                $value = (int)$value;
            }

            // ajustar pilha se subimos de volta
            while ($indent < end($indentStack)) {
                array_pop($stack);
                array_pop($indentStack);
            }

            $stack[count($stack) - 1][$key] = $value;

            // se for array, desce nível
            if (is_array($value)) {
                $stack[] = &$stack[count($stack) - 1][$key];
                $indentStack[] = $indent + 4; // assume indentação de 4
            }
        }
    }

    return $result;
}

