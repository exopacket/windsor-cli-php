<?php

namespace Windsor\CLI\Utils;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Env
{

    private static Env $global;
    private static bool $valid = false;

    public function __construct(string $filename = null)
    {
        $root = realpath(".");
        $env = (isset($filename)) ? Dotenv::createImmutable($root, $filename) : Dotenv::createImmutable($root);
        //$env->required([]);
        try {
            $env->load();
            self::$valid = true;
        } catch (InvalidPathException $e) {
            error_log("Could not find an environment file, defaulting to CLI.php class in root directory. ");
        }
    }

    public static function get(string $key)
    {
        if(!self::$valid) return null;
        if(!isset(self::$global)) self::$global = new self(".env");
        return $_ENV[$key];
    }

}