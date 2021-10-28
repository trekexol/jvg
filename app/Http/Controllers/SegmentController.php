<?php

namespace App\Http\Controllers;

use App\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SegmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $user       =   auth()->user();
        $users_role =   $user->role_id;
        if($users_role == '1'){
           $segments      =   Segment::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.segments.index',compact('segments'));
      
    }

    public function create()
    {

        

        return view('admin.segments.create');
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
          
            'description'         =>'required|max:255',
            
           
        ]);

        $users = new Segment();
        $users->setConnection(Auth::user()->database_name);

        $users->description = request('description');
        
        $users->status = 1;

        $users->save();

        return redirect('/segments')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $user                   = Segment::on(Auth::user()->database_name)->find($id);
        
        return view('admin.segments.edit',compact('user'));
    }

   


    public function update(Request $request,$id)
    {
       
        $request->validate([
          
            'description'      =>'required|string|max:255',
            
        ]);

        

        $user          = Segment::on(Auth::user()->database_name)->findOrFail($id);
        $user->description        = request('description');
       
     

        $user->save();


        return redirect('/segments')->withSuccess('Registro Guardado Exitoso!');

    }


}
