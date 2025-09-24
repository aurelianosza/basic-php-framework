<?php

namespace Core;

use Error;

class Config {

    private static ?Config $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            throw new Error("Config not started yet, please call init() before get config instance.");
        }

        return self::$instance;
    }

    public static function init(string $configFilePath): void
    {
        if (self::$instance !== null) {
            throw new Error("Config already started.");
        }

        self::$instance = new Config($configFilePath);
    }

    private array $configSet = [];

    private function __construct(string $configFilePath)
    {
        $fileRawContent = $this->getFileRawContent($configFilePath);

        $this->configSet = mini_yaml_parse($fileRawContent);
    }

    private function getFileRawContent(string $path): string
    {
        $fileRawContent = file_get_contents($path);
        
        if (!$fileRawContent) {
            throw new Error("File config $path not found.");
        }

        return $fileRawContent;
    }

    public function getConfig(string $path): mixed
    {
        return dot_get($this->configSet, $path, null);
    }
}
