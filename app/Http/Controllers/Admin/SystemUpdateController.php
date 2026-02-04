<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class SystemUpdateController extends Controller
{
    public function index()
    {
        // Auto-run migration if table is missing (System Initialization)
        if (!Schema::hasTable('system_updates')) {
            Artisan::call('migrate', ['--force' => true]);
        }

        // Fetch update history (now safe to query)
        $updates = DB::table('system_updates')->orderBy('created_at', 'desc')->get();
        return view('backEnd.system.update', compact('updates'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'update_file' => 'required|file|mimes:zip',
            'version' => 'required|string',
            'changelog' => 'nullable|string',
        ]);

        $file = $request->file('update_file');
        $destinationPath = base_path('/'); // Root directory of the application
        $zip = new ZipArchive;

        if ($zip->open($file->getRealPath()) === TRUE) {
            // Extract the zip file to the application root
            $zip->extractTo($destinationPath);
            $zip->close();

            // Run Migrations
            Artisan::call('migrate', ['--force' => true]);

            // Clear Cache
            Artisan::call('optimize:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');

            // Log the update
            DB::table('system_updates')->insert([
                'version' => $request->version,
                'changelog' => $request->changelog,
                'status' => 'success',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'System updated successfully! Version: ' . $request->version);
        } else {
            return redirect()->back()->with('error', 'Failed to open the zip file.');
        }
    }
}
