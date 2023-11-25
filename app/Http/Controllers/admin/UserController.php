<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::latest();
        if (!empty($request->get('keyword'))) {
            $users = $users->where('users.name', 'like', '%'.$request->get('keyword').'%');
            $users = $users->orWhere('users.email', 'like', '%'.$request->get('keyword').'%');
         }
        $users = $users->paginate(10);

        return view('admin.users.list',[
            'users' => $users
        ]);        
    }

    public function create(Request $request) {
        return view('admin.users.create');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            $request->session()->flash('success', 'User added successfully');

            return response()->json([
                'status' => true,
                'message' => 'User added successfully'
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

        $user = User::find($id);
  
        if(!$user){
            $request->session()->flash('error', 'User not found');
            return redirect()->route('users.index');
        }
      //   dd($user);
        
      $data['user'] = $user;
      return view('admin.users.edit', $data); 
                
    }

    public function update(Request $request, $id){

        $user = User::find($id);

        if(!$user){
            $request->session()->flash('error', 'User not found');
            return response()->json([
                'status' => true,
                'message' => 'User not found'
            ]);
            // return redirect()->route('users.index');
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:users,email,'.$id.',id',
        ]);

        if($validator->passes()){

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $request->session()->flash('success', 'User updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully'
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
  
        $user = User::find($id);
        
        // print_r($user);
        // die;
        if( !$user ){
            $request->session()->flash('error', 'User not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'User not found'
            ]);
        }
  
        $user->delete();
  
        $request->session()->flash('success', 'User deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully'
            ]);
    }
}
