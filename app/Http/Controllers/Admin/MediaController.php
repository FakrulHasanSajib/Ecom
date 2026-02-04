<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    // ১. গ্যালারি পেজ
    public function index()
    {
        // $this->syncExternalFiles(); // <--- এই লাইনটি বন্ধ করা হয়েছে যাতে ডিলিট করার পর ছবি অটোমেটিক ফিরে না আসে।
        
        $media = Media::latest()->paginate(20);
        return view('backEnd.admin.gallery.index', compact('media'));
    }

    // ২. ম্যানুয়াল সিংক বাটন (বাটন ক্লিক করলেই কেবল সিংক হবে)
    public function syncFiles()
    {
        $count = $this->syncExternalFiles();
        return back()->with('success', 'Sync Complete! Total new files found: ' . $count);
    }

    // ৩. মূল সিংক লজিক (প্রাইভেট হেল্পার ফাংশন)
    private function syncExternalFiles()
    {
        $count = 0;
        
        // আপনার ম্যানুয়াল আপলোড ফোল্ডার (public/uploads)
        $publicPath = public_path('uploads'); 

        if (File::exists($publicPath)) {
            $files = File::allFiles($publicPath);

            foreach ($files as $file) {
                $filename = $file->getFilename();
                $extension = strtolower($file->getExtension());
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($extension, $allowed)) {
                    
                    // স্টোরেজ ফোল্ডারে কপি করে নেওয়া (যাতে ব্লেড ফাইলে পাথ ঠিক থাকে)
                    if (!Storage::disk('public')->exists('uploads/' . $filename)) {
                        Storage::disk('public')->putFileAs('uploads', $file, $filename);
                    }

                    // ডাটাবেস চেক
                    $exists = Media::where('filename', $filename)->exists();

                    if (!$exists) {
                        Media::create([
                            'filename'  => $filename,
                            'path'      => 'uploads/' . $filename,
                            'extension' => $extension,
                            'mime_type' => mime_content_type($file->getPathname()),
                            'size'      => $file->getSize(),
                        ]);
                        $count++;
                    }
                }
            }
        }
        return $count;
    }

    // ৪. ছবি আপলোড (Multiple Upload Logic)
    public function store(Request $request)
    {
        // ✅ 1. Server-side validation (UPDATED FOR 10MB)
        $request->validate([
            'files'   => 'required',
            // max:10240 KB = 10 MB
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', 
        ]);

        $count = 0;

        // ✅ 2. File upload logic
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                // Unique filename
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Save to storage/app/public/uploads
                $path = $file->storeAs('uploads', $filename, 'public');

                // Save to database
                Media::create([
                    'filename'  => $filename, // ⚠️ original name না দিয়ে saved name রাখা safe
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getClientMimeType(),
                    'size'      => $file->getSize(),
                ]);

                $count++;
            }
        }

        // ❌ যদি কোনো ফাইলই আপলোড না হয়
        if ($count === 0) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No file uploaded!',
                ], 422);
            }

            return redirect()->back()->withErrors('No file uploaded!');
        }

        // ✅ 3. AJAX request → JSON response
        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => $count . ' images uploaded successfully!',
            ], 200);
        }

        // ✅ 4. Normal form submit → redirect back
        return redirect()->back()->with(
            'success',
            $count . ' images uploaded successfully!'
        );
    }

    // ৫. ছবি পারমানেন্ট ডিলিট (Hard Delete)
    // ৫. ছবি পারমানেন্ট ডিলিট (Hard Delete)
public function destroy($id)
{
    $media = Media::findOrFail($id);

    // ১. যে ফোল্ডার থেকে 'Sync' করা হয়, সেটি সবার আগে টার্গেট করা
    // আপনার সিংক ফাংশন public_path('uploads') চেক করে, তাই এটি ডিলিট করা সবচেয়ে জরুরি।
    $syncSourcePath = public_path('uploads/' . $media->filename);

    if (file_exists($syncSourcePath)) {
        try {
            @unlink($syncSourcePath); // ফোল্ডার থেকে ডিলিট
        } catch (\Exception $e) {
            // যদি পারমিশন সমস্যার কারণে ডিলিট না হয়, তাহলে এরর লগ হবে কিন্তু কোড থামবে না
            \Log::error("Failed to delete from uploads: " . $e->getMessage());
        }
    }

    // ২. অন্যান্য সম্ভাব্য পাথগুলো থেকেও ডিলিট করা (Storage বা Symlink)
    $otherPaths = [
        storage_path('app/public/' . $media->path),      // মেইন স্টোরেজ
        public_path($media->path),                       // পাবলিক শর্টকাট
        public_path('storage/' . $media->path)           // সিমলিংক পাথ
    ];

    foreach ($otherPaths as $path) {
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    // ৩. সবশেষে ডাটাবেস থেকে রেকর্ড ডিলিট
    $media->delete();

    return back()->with('success', 'Image permanently deleted!');
}

    public function getMediaList()
    {
        $media = Media::latest()->get();
        return response()->json($media);
    }
    
}