<?php

namespace tests\Utils;

class FakerMini
{
    private static array $firstNames = [
        'Alice', 'Bob', 'Carlos', 'Diana', 'Eve', 'Felipe', 'Gabriela'
    ];

    private static array $lastNames = [
        'Silva', 'Santos', 'Oliveira', 'Costa', 'Pereira', 'Almeida'
    ];

    private static array $domains = [
        'example.com', 'test.com', 'mail.com'
    ];

    public static function number(int $min = 0, int $max = 1000): int
    {
        return random_int($min, $max);
    }

    public static function name(): string
    {
        return self::$firstNames[array_rand(self::$firstNames)] . ' ' .
               self::$lastNames[array_rand(self::$lastNames)];
    }

    public static function email(): string
    {
        $name = strtolower(str_replace(' ', '.', self::name()));
        $domain = self::$domains[array_rand(self::$domains)];
        return "$name@$domain";
    }

    public static function string(int $length = 10): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
    }

    public static function random(array $arr): mixed
    {
        return $arr[array_rand($arr)];
    }

     public static function url(): string
    {
        $protocol = ['http', 'https'][array_rand(['http', 'https'])];
        $domain = self::$domains[array_rand(self::$domains)];
        $path = strtolower(self::string(5));  // caminho aleatório
        $query = http_build_query([
            'id' => self::number(1, 1000),
            'ref' => self::string(3)
        ]);

        return "$protocol://$domain/$path?$query";
    }
}
