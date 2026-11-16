<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class TestImageController extends Controller
{
    public function testImage($folder, $filename)
    {
        $path = storage_path('app/public/' . $folder . '/' . $filename);

        if (!File::exists($path) || is_dir($path)) {
            return redirect('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600');
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = new Response($file, 200);
        $response->header('Content-Type', $type);

        return $response;
    }
}