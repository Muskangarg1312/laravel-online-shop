<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{   
    public function index(Request $request) {
        
        $brands = Brand::latest();

        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $brands = $brands->paginate(10);

        //dd($brands);
        $data['brands'] = $brands;
        return view('admin.brands.list', $data);
    }

    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if($validator->passes()){

            $input = $request->only(['name', 'slug', 'status']);

            Brand::create($input);

            $request->session()->flash('success', 'Brand added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);

        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }
    }

    public function edit($id, Request $request){

        $brand = Brand::find($id);
  
          if(!$brand){
              $request->session()->flash('error', 'Brand not found');
              return redirect()->route('brands.index');
          }
        //   dd($brand);
          
        $data['brand'] = $brand;
        return view('admin.brands.edit', $data); 
     }
  
     public function update($id, Request $request) {
  
        $brand = Brand::find($id);
  
        if( !$brand ){
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }
  
        $validator = Validator::make($request->all(),[
           'name' => 'required',
           'slug' => 'required|unique:brands,slug,'.$brand->id.',id'
       ]);
  
       if($validator->passes()){
  
        $input = $request->only(['name', 'slug', 'status']);

        // dd($input);

        $brand->update($input);
  
        $request->session()->flash('success', 'Brand updated successfully');
  
              return response()->json([
                  'status' => true,
                  'message' => 'Brand updated successfully'
              ]);
       }
       else{
           return response()->json([
              'status' => false,
              'errors' => $validator->errors()
           ]);
        }
          
      }
  
      public function destroy($id, Request $request) {
  
        $brand = Brand::find($id);
        
        // print_r($brand);
        // die;
        if( !$brand ){
            $request->session()->flash('error', 'Brand not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brand not found'
            ]);
        }
  
        $brand->delete();
  
        $request->session()->flash('success', 'Brand deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Brand deleted successfully'
            ]);
    }
  
}
