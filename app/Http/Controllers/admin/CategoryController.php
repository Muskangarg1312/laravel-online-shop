<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;
// use File;

class CategoryController extends Controller
{
    public function index(Request $request) {
        
        $categories = Category::with('media')->latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%'.$request->get('keyword').'%');
        }

        

        $categories = $categories->paginate(10);

        //dd($categories);
        $data['categories'] = $categories;
        return view('admin.category.list', $data);
    }

    public function create() {
        return view('admin.category.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if($validator->passes()){

            $input = $request->only(['name', 'slug', 'status', 'showHome']);
            $input['image'] = $request->image_id;

            Category::create($input);

            // $category = new Category();
            // $category->name = $request->name;
            // $category->slug = $request->slug;
            // $category->status = $request->status;
            // $category->save();

            // // Save Image Here

            // if(!empty($request->image_id)){
            //     $tempImage = TempImage::find($request->image_id);
            //     $extArray = explode('.', $tempImage->name);
            //     $ext = last($extArray);

            //     $newImageName = $category->id.'.'.$ext;
            //     $sPath = public_path().'/temp/'.$tempImage->name; // Source Path
            //     $dPath = public_path().'/uploads/category/'.$newImageName; // Destination Path
            //     File::copy($sPath, $dPath);

            //     // Generate Image Thumbnail
            //     $dPath = public_path().'/uploads/category/thumb/'.$newImageName; // Destination Path
            //     $img = Image::make($sPath);
            //     $img->resize(450, 600);
            //     $img->save($dPath);

            //     $category->image = $newImageName;
            //     $category->save();
            // }
            
            $request->session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);

        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }
    }

    public function edit($categoryId, Request $request) {
        //echo $categoryId;
        $category = Category::with('media')->find($categoryId);

        if(!$category){
            $request->session()->flash('error', 'Category not found');
            return redirect()->route('categories.index');
        }
        // dd($category);
        return view('admin.category.edit', compact('category'));
    }
    
    public function update($categoryId, Request $request) {

        $category = Category::find($categoryId);
        
        // print_r($category);
        // die;
        if( !$category ){
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if($validator->passes()){

            $input = $request->only(['name', 'slug', 'status', 'showHome']);
            $input['image'] = $request->image_id;

            //dd($input);

            $category->update($input);
            $request->session()->flash('success', 'Category updated successfully');
            return response()->json([
                'status' => true,
                'input' => $input,
                'message' => 'Category updated successfully'
            ]);

        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }
        
    }
    
    public function destroy($categoryId, Request $request) {

        $category = Category::find($categoryId);
        
        // print_r($category);
        // die;
        if( !$category ){
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        // File::delete(public_path().'storage/'.$category->image);
        $year = date('Y', strtotime($category->created_at)); // Extract the year
        $month = date('m', strtotime($category->created_at)); // Extract the month
        $imageId = $category->image;
        $image = TempImage::where('id',$imageId)->first();
        // print_r($image->name);
        // die;
        $filePath = public_path('storage/'. $image->name);
        File::delete($filePath);

        // print_r($filePath);
        // die;
        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully'
            ]);
    }
}
