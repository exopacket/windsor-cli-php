<?php

namespace Windsor\CLI;

use Minicli\App;
use Minicli\Command\CommandCall;
use Windsor\CLI\DefaultCommands\Work;

abstract class CommandRegistry
{

    private string $projectDir;

    protected abstract function map(): array;

    public function __construct(App $app, CommandCall $call, string $projectDir)
    {
        $this->projectDir = $projectDir;
        $map = $this->map();
        if(!array_key_exists("work", $map)) $map['work'] = Work::class;
        foreach($map as $command => $class) {
            $this->register($command, $class, $app, $call);
        }
    }

    public static function createApp($argv, $projectDir)
    {
        $app = new App();
        $input = new CommandCall($argv);
        $class = get_called_class();
        new $class($app, $input, $projectDir);
        $app->runCommand($input->getRawArgs());
    }

    private function register(string $command, string $class, App $app, CommandCall $call) {
        $dir = $this->projectDir;
        $app->registerCommand(strtolower($command), function () use ($class, $app, $call, $dir) {
            $command = new $class($app, $call, $dir);
        });
    }

}