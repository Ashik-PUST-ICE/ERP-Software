<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Illuminate\Support\Facades\File;

class VersionUpdateController extends Controller
{
    use ResponseTrait;

    protected $logger;
    public $fileSystem;

    public function __construct()
    {
        $this->logger = new Logger(storage_path('logs/update.log'));
        $this->fileSystem = new Filesystem();
    }

    public function versionFileUpdate(Request $request)
    {
        $data['title'] = __(config('app.version_updater_name', 'Ashik Version Update'));
        $data['activeVersionUpdate'] = 'active';
        $data['latestVersion'] = config('app.current_version');
        $data['latestBuildVersion'] = config('app.build_version');

        $path = storage_path('app/source-code.zip');
        if (file_exists($path)) {
            $data['uploadedFile'] = 'source-code.zip';
        } else {
            $data['uploadedFile'] = '';
        }

        return view('auto_posts.super_admin.version_update.create', $data);
    }

    public function versionFileUpdateStore(Request $request)
    {
        try {
            $request->validate([
                'update_file' => 'bail|required|mimes:zip'
            ], [
                'update_file.required' => __('The update file is required.'),
                'update_file.mimes' => __('The file must be a zip file.')
            ]);

            set_time_limit(1200);
            $path = storage_path('app/source-code.zip');

            if (file_exists($path)) {
                $this->fileSystem->delete($path);
            }

            $request->file('update_file')->storeAs('', 'source-code.zip', 'local');
            return response()->json(['success' => true, 'message' => __('File uploaded successfully.')]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => ['update_file' => $e->errors()['update_file'] ?? [__('Validation failed.')]]
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function executeUpdate()
    {
        set_time_limit(1200);
        $path = storage_path('app/source-code.zip');
        $demoPath = storage_path('app/updates');

        $response['success'] = false;
        $response['message'] = 'File not exist on storage!';

        $this->logger->log('Update Start', '==========');
        if (file_exists($path)) {
            $this->logger->log('File Found', 'Success');
            $zip = new ZipArchive;

            if (is_dir($demoPath)) {
                $this->logger->log('Updates directory', 'exist');
                $this->logger->log('Updates directory', 'deleting');
                $this->fileSystem->deleteDirectory($demoPath);
                $this->logger->log('Updates directory', 'deleted');
            }

            $this->logger->log('Updates directory', 'creating');
            $this->fileSystem->makeDirectory($demoPath, 0777, true, true);
            $this->logger->log('Updates directory', 'created');

            $this->logger->log('Zip', 'opening');
            $res = $zip->open($path);

            if ($res === true) {
                $this->logger->log('Zip', 'Open successfully');
                try {
                    $this->logger->log('Zip Extracting', 'Start');
                    $res = $zip->extractTo($demoPath);
                    $this->logger->log('Zip Extracting', 'END');
                    $this->logger->log('Get update note', 'START');
                    $versionFile = file_get_contents($demoPath . DIRECTORY_SEPARATOR . 'update_note.json');
                    $updateNote = json_decode($versionFile);
                    if (!is_object($updateNote) || !isset($updateNote->build_version, $updateNote->root_path, $updateNote->code_path)) {
                        throw new Exception('Invalid Ashik update package: update_note.json is incomplete.');
                    }
                    $this->logger->log('Get update note', 'END');
                    $this->logger->log('Get Build Version from update note', 'START');
                    $codeVersion = (int) $updateNote->build_version;
                    $this->logger->log('Get Build Version from update note', 'END');
                    $this->logger->log('Get Root Path from update note', 'START');
                    $codeRootPath = $updateNote->root_path;
                    $this->logger->log('Get Root Path from update note', 'END');
                    $this->logger->log('Get current version', 'START');
                    $currentVersion = getCustomerCurrentBuildVersion();
                    $this->logger->log('Get current version', 'END');
                    $this->logger->log('Checking if updatable version locally', 'START');
                    if ($codeVersion > $currentVersion) {
                                $this->logger->log('Checking if updatable code', 'True');
                                $this->logger->log('Move file', 'START');

                                $allMoveFilePath = (array)($updateNote->code_path);
                                foreach ($allMoveFilePath as $filePath => $type) {
                                    $this->logger->log('Move file', 'Start ' . $demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath . ' to ' . base_path($filePath));
                                    if ($type == 'file') {
                                        $this->fileSystem->copy($demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath, base_path($filePath));
                                    } else {
                                        $this->fileSystem->copyDirectory($demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath, base_path($filePath));
                                    }
                                    $this->logger->log('Move file', 'END ' . $demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath . ' to ' . base_path($filePath));
                                }
                                $response['success'] = true;
                                $response['message'] = 'Successfully done';
                                Artisan::call('migrate', ['--force' => true]);
                                setCustomerBuildVersion($codeVersion);
                                setCustomerCurrentVersion();
                                $this->logger->log('Move file', 'Done');
                    } else {
                        $response['message'] = 'Your code is not up to date';
                        $this->logger->log('Version', 'Not matched');
                    }

                    $this->logger->log('Demo extracted path', 'Deleting');
                    $this->fileSystem->deleteDirectory($demoPath);

                    $zipPath = storage_path('app/source-code.zip');
                    if (file_exists($zipPath)) {
                        $this->fileSystem->delete($zipPath);
                    }
                    $this->logger->log('Demo extracted path', 'Deleted');
                } catch (Exception $e) {
                    Log::info($e->getMessage());
                    $response['message'] = $e->getMessage();
                    $this->logger->log('Exception', $e->getMessage());
                }
                $zip->close();
            } else {
                $this->logger->log('Zip', 'Open failed');
            }
        }

        $this->logger->log('', '===============Update END==============');

        return $response;
    }

    public function versionUpdateExecute()
    {
        $response = $this->executeUpdate();
        if ($response['success'] == true) {
            return back();
        }
        return back()->with('error', json_encode($response['message']));
    }

    public function versionFileUpdateDelete()
    {
        $path = storage_path('app/source-code.zip');

        if (file_exists($path)) {
            $this->fileSystem->delete($path);
        }

        return redirect()->back()->with('success', __('File deleted successfully.'));
    }

    public function versionCheck()
    {
        return ['App build version' => config('app.build_version'), 'Customer current build version' => getCustomerCurrentBuildVersion()];
    }

    public function pathFile()
    {
        $data['title'] = __('Version Update');

        return view('super_admin.version_update.update-path-file', $data);
    }

    public function storePathFile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'path' => 'required|string',
            'file' => 'required|file',
        ]);

        // Get the full path where the file should be stored
        $filePath = base_path($request->path);

        // Ensure the directory exists
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Move the uploaded file to the desired location, overwriting if necessary
        $file = $request->file('file');
        $file->move($directory, basename($filePath));

        // Redirect back with a success message
        return redirect()->back()->with('success', 'File stored successfully.');
    }

    public function downloadPathFile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'path' => 'required|string',
        ]);

        // Get the full path of the file to be downloaded
        $filePath = base_path($request->path);

        // Check if the file exists
        if (!File::exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        // Return the file for download
        return response()->download($filePath);
    }
}
