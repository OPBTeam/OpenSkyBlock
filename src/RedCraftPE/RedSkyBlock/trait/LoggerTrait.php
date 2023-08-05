<?php

declare(strict_types = 1);

namespace RedCraftPE\RedSkyBlock\trait;

use phuongaz\easylog\EasyLog;
use phuongaz\easylog\LogLevel;


trait LoggerTrait {

    private static EasyLog $log;

    public static function initLogger(): void{
        $log = new EasyLog();
        $log->init("OpenSkyBlock");
        self::$log = $log;
    }

    public static function log(string $message, LogLevel $level = LogLevel::INFO) :void{
        self::$log->log($message, $level);
    }

    public static function logSub(string $sub, string $message, LogLevel $level = LogLevel::INFO) :void{
        self::$log->logSub($sub, $message, $level);
    }
}