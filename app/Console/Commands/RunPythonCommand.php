<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunPythonCommand extends Command
{
    protected $signature = 'run:python';
    protected $description = 'Run a Python script';

    public function handle()
    {
        $inputData = "Hello from Artisan command";

        // $pythonCommand = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'python' : 'python3';
        $pythonPath = env("python_path");

        $process = new Process([$pythonPath, public_path('Python/test.py')]);
        $process->setInput($inputData);
        $process->run();

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $this->info($output);
        } else {
            $errorOutput = $process->getErrorOutput();
            $this->error('Error executing Python script: ' . $errorOutput);
        }
    }
}
