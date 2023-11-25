<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request) {

        $image = $request->image;

      
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'null';
        $productImage->save();
        
        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Large Image

        
        $destPath = public_path(). '/storage/product/large/'.$imageName;
        $image = Image::make($sourcePath);
        $image->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save($destPath);
        
        // Small Image
        
        $destPath = public_path(). '/storage/product/small/'.$imageName;
        $image = Image::make($sourcePath);
        $image->fit(300,300);
        $image->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('storage/product/small/'.$productImage->image),
            'message' => 'Image uploaded successfully'
        ]);
    }

    public function destroy(Request $request) {

        $productImage = ProductImage::find($request->id);

        if (empty($productImage)) {
            return response()->json([
                'status' => false,
                'message' => 'Image not Found'
            ]);
        }

        // Delete images from Folder

        File::delete(public_path('storage/product/large/'.$productImage->image));
        File::delete(public_path('storage/product/small/'.$productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
}
