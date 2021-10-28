<?php

namespace App\Http\Controllers;

use App\Subsegment;
use App\ThreeSubsegment;
use App\TwoSubsegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreeSubSegmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request, $id_subsegment = null){
        //validar si la peticion es asincrona
        if($request->ajax()){
            try{
                
                $subsegment = ThreeSubsegment::on(Auth::user()->database_name)->select('id','description')->where('twosubsegment_id',$id_subsegment)->orderBy('description','asc')->get();
                return response()->json($subsegment,200);
            
            }catch(Throwable $th){
                return response()->json(false,500);
            }
        }
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
           $subsegments      =   ThreeSubsegment::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

        return view('admin.threesubsegments.index',compact('subsegments'));
      
    }

    public function create()
    {
        $twosubsegments   = TwoSubsegment::on(Auth::user()->database_name)->orderBy('description', 'asc')->get();

        return view('admin.threesubsegments.create',compact('twosubsegments'));
    }

    public function store(Request $request)
    {
       
        $data = request()->validate([
            'description'         =>'required|max:255',
            'segment_id'         =>'required',
            
        ]);

        $users = new ThreeSubsegment();
        $users->setConnection(Auth::user()->database_name);

        $users->description = request('description');
        $users->twosubsegment_id = request('segment_id');
        $users->status = 1;

        $users->save();
        return redirect('/threesubsegments')->withSuccess('Registro Exitoso!');
    }
    
    public function messages()
    {
        return [
            'segment_id.required' => 'A title is required',
            'segment_id'  => 'A message is required',
        ];
    }


    public function edit($id)
    {

        $var        = ThreeSubsegment::on(Auth::user()->database_name)->find($id);
        $twosubsegments   = TwoSubsegment::on(Auth::user()->database_name)->get();

        return view('admin.threesubsegments.edit',compact('var','twosubsegments'));
    }

   


    public function update(Request $request,$id)
    {
        $subsegment =  ThreeSubsegment::on(Auth::user()->database_name)->find($id);
       
        $subsegment_status = $subsegment->status;

        
        $request->validate([
            'description'         =>'required|max:255',
            'segment_id'  => 'required|integer',
            
        ]);

        
        $var = ThreeSubsegment::on(Auth::user()->database_name)->findOrFail($id);
        $var->description         = request('description');
        $var->twosubsegment_id       = request('segment_id');
      
       
        if(request('status') == null){
            $var->status = $subsegment_status;
        }else{
            $var->status = request('status');
        }
       

        $var->save();


        return redirect('/threesubsegments')->withSuccess('Registro Guardado Exitoso!');

    }
}
