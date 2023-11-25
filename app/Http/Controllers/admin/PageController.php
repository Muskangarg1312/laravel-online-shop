<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class PageController extends Controller
{
    public function index(Request $request) {
        $pages = Page::latest();
        if (!empty($request->get('keyword'))) {
            $pages = $pages->where('pages.name', 'like', '%'.$request->get('keyword').'%');
         }
        $pages = $pages->paginate(10);

        return view('admin.pages.list',[
            'pages' => $pages
        ]);   
    }

    public function create() {
        return view('admin.pages.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',            
        ]);

        if($validator->passes()){

            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            $request->session()->flash('success', 'Page added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Page added successfully'
            ]);

        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id) {

        $page = Page::find($id);
  
        if(!$page){
            $request->session()->flash('error', 'Page not found');
            return redirect()->route('pages.index');
        }
      //   dd($page);
        
      $data['page'] = $page;
      return view('admin.pages.edit', $data); 
                
    }

    public function update(Request $request, $id) {
        $page = Page::find($id);
  
        if(!$page){
            $request->session()->flash('error', 'Page not found');
            return response()->json([
                'status' => true,
                'message' => 'Page not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',            
        ]);

        if($validator->passes()){

            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            $request->session()->flash('success', 'Page updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Page updated successfully'
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
  
        $page = Page::find($id);
  
        if(!$page){
            $request->session()->flash('error', 'Page not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Page not found'
            ]);
        }
  
        $page->delete();
  
        $request->session()->flash('success', 'Page deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Page deleted successfully'
            ]);
    }
}
