<?php
namespace dwes\app\utils;
use Monolog;
use dwes\app\utils\Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
class MyLog
{
    /**
     * @var \Monolog\Logger
     */
    private $log;

    private $level;
    private function __construct(string $filename, $level)
    {
        $this->level = $level;
        $this->log = new Monolog\Logger('name');
        $this->log->pushHandler(new Monolog\Handler\StreamHandler($filename, $this->level));
    }
    public static function load(string $filename, $level = Level::Info): MyLog
    {
        return new MyLog($filename, $level);
    }
    public function add(string $message): void
    {
       $this->log->log($this->level, $message);
    }
}