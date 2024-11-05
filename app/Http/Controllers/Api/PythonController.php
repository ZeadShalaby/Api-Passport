<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonController extends Controller
{
    use ResponseTrait;


    // ?todo use the first way to run the python script  (Artisan)
    public function artisan(Request $request)
    {
        try {
            // ? run the artisan command
            Artisan::call('run:python');
            $output = Artisan::output();
            return $this->returnData("data", $output);
        } catch (Exception $e) {
            return $this->returnError(500, $e->getCode(), $e->getMessage());
        }
    }

    // ?todo use the second way to run the python script  (Execute)
    public function execute(Request $request)
    {
        try {
            $inputData = "Hello from execute method";
            $output = [];
            $return_var = 0;

            $pythonScriptPath = public_path('Python/test.py');

            exec('python ' . escapeshellarg($pythonScriptPath) . ' ' . escapeshellarg($inputData), $output, $returnCode);
            if ($return_var === 0) {
                return response()->json(['status' => 'success', 'output' => implode("\n", $output)]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error executing command']);
            }
        } catch (Exception $e) {
            return $this->returnError(500, $e->getCode(), $e->getMessage());
        }
    }


    // ?todo use the third way to run the python script  (Symfony Process)
    public function process(Request $request)
    {

        $inputData = "Hello from Symfony Process";

        // ?make a new process
        $pythonPath = env("python_path");
        $process = new Process([$pythonPath, public_path('Python/test.py')]);
        $process->setInput($inputData);
        $process->run(); // ?run the process

        // ?check if the process is successful
        if ($process->isSuccessful()) {
            return response()->json(['output' => $process->getOutput()]);
        } else {
            throw new ProcessFailedException($process);
        }
    }
}
