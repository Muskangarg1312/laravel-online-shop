<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Image;
use File;

class TempImagesController extends Controller
{
    // public function create(Request $request) {

    //     if( !$request->hasFile('image') ) {
    //         return response()->json(['error' => 'File is required *']);
    //     }

    //     $image = $request->file('image');

    //     if (!empty($image)) {
    //         $ext = $image->getClientOriginalExtension();
    //         $newName = time(). '.' . $ext; 

    //         $filename = md5(time());
    //         $newFilename = $filename . '.' . $ext;

    //         $year = date('Y');
    //         $month = date('m');
    //         $path = storage_path("app/public/category/$year/$month");
            

    //         File::isDirectory($path) or File::makeDirectory($path, 777, 1, 1);

    //         $img = Image::make($image->getRealPath())->resize(300, 300);
    //         $img->save("$path/$newFilename");

    //         $file = "category/$year/$month/$newFilename";

    //         $file = TempImage::create(['name' => $file]);

    //         // $tempImage = new TempImage();
    //         // $tempImage->name = $newName;
    //         // $tempImage->save();

    //         // $image->move(public_path(). '/temp', $newName);

    //         return response()->json([
    //             'status' => true,
    //             'file' => $file,
    //             'message' => 'Image uploaded successfully'
    //         ]);

    //     }
    // }

    public function create(Request $request) {

        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'File is required *']);
        }
    
        $image = $request->file('image');
    
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time(). '.' . $ext; 
    
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // Use the original filename without extension
            $newFilename = $filename . '.' . $ext;
    
            $year = date('Y');
            $month = date('m');
            $path = storage_path("app/public/category/$year/$month");
    
            File::isDirectory($path) or File::makeDirectory($path, 777, true, true);
    
            $img = Image::make($image->getRealPath())->fit(450, 600);
            $img->save("$path/$newFilename");
    
            $file = "category/$year/$month/$newFilename";
    
            $existingFile = TempImage::where('name', $file)->first();
    
            if (!$existingFile) {
                $existingFile = TempImage::create(['name' => $file]);
            }
    
            return response()->json([
                'status' => true,
                'file' => $existingFile,
                'message' => 'Image uploaded successfully'
            ]);
        }
    }  

    // public function upload(Request $request) {
    //     if (!$request->hasFile('image')) {
    //         return response()->json(['error' => 'File is required *']);
    //     }
    
    //     $image = $request->file('image');
    
    //     if (!empty($image)) {
    //         $ext = $image->getClientOriginalExtension();
    //         $newName = time(). '.' . $ext; 
    
    //         $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // Use the original filename without extension
    //         $newFilename = $filename . '.' . $ext;
    
    //         $year = date('Y');
    //         $month = date('m');
    //         $path = storage_path("app/public/product/large/$year/$month");
    
    //         File::isDirectory($path) or File::makeDirectory($path, 777, true, true);
    
    //         // $img = Image::make($image->getRealPath())->fit(450, 600);
    //         $img = Image::make($image->getRealPath());
    //         $img->resize(1400, null, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });
    //         $img->save("$path/$newFilename");
    
    //         $file = "product/large/$year/$month/$newFilename";
    
    //         $existingFile = TempImage::where('name', $file)->first();
    
    //         if (!$existingFile) {
    //             $existingFile = TempImage::create(['name' => $file]);
    //         }
    
    //         return response()->json([
    //             'status' => true,
    //             'image_id' => $existingFile->id,
    //             'file' => $existingFile,
    //             'message' => 'Image uploaded successfully'
    //         ]);
    //     }

    // }

    // public function upload(Request $request) {
    //     if (!$request->hasFile('image')) {
    //         return response()->json(['error' => 'File is required *']);
    //     }
    
    //     $image = $request->file('image');
    
    //     if (!empty($image)) {
    //         $ext = $image->getClientOriginalExtension();
    //         $newName = time(). '.' . $ext; 
    
    //         $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // Use the original filename without extension
    //         $newFilename = $filename . '.' . $ext;
    
    //         $year = date('Y');
    //         $month = date('m');
    //         $basePath = storage_path("app/public/product");
    
    //         $largePath = "$basePath/large/$year/$month";
    //         $smallPath = "$basePath/small/$year/$month";
    
    //         File::isDirectory($largePath) or File::makeDirectory($largePath, 777, true, true);
    //         File::isDirectory($smallPath) or File::makeDirectory($smallPath, 777, true, true);
    
    //         $img = Image::make($image->getRealPath());
    
    //         // Save Large Image
    //         $largeImage = $img->resize(1400, null, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });
    //         $largeImage->save("$largePath/$newFilename");
    
    //         // Save Small Image
    //         $smallImage = Image::make($image->getRealPath())->fit(300, 300);
    //         // $smallImage = $img->resize(300, 300, function ($constraint) {
    //         //     $constraint->aspectRatio();
    //         // });
    //         $smallImage->save("$smallPath/$newFilename");
    
    //         $largeFile = "product/large/$year/$month/$newFilename";
    //         $smallFile = "product/small/$year/$month/$newFilename";
    
    //         $existingLargeFile = TempImage::where('name', $largeFile)->first();
    //         $existingSmallFile = TempImage::where('name', $smallFile)->first();
    
    //         if (!$existingLargeFile) {
    //             $existingLargeFile = TempImage::create(['name' => $largeFile]);
    //         }
    
    //         if (!$existingSmallFile) {
    //             $existingSmallFile = TempImage::create(['name' => $smallFile]);
    //         }
    
    //         return response()->json([
    //             'status' => true,
    //             'large_image_id' => $existingLargeFile->id,
    //             'small_image_id' => $existingSmallFile->id,
    //             'large_file' => $existingLargeFile,
    //             'small_file' => $existingSmallFile,
    //             'message' => 'Images uploaded successfully'
    //         ]);
    //     }
    // }

    public function upload(Request $request) {

        $image = $request->image;

        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = time(). '.' . $ext; 

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path(). '/temp/', $newName);

            // Generate Thumbnails

            $sourcePath = public_path(). '/temp/'.$newName;
            $destPath = public_path(). '/temp/thumb/'.$newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/'.$newName),
                'message' => 'Image uploaded successfully'
            ]);
        }
    }

    
}
