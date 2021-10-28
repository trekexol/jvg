<?php

namespace App\Http\Controllers;


use App\Segment;
use App\Subsegment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubsegmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request, $id_segment = null){
        //validar si la peticion es asincrona
        if($request->ajax()){
            try{
                
                $subsegment = Subsegment::on(Auth::user()->database_name)->select('id','description')->where('segment_id',$id_segment)->orderBy('description','asc')->get();
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
           $subsegments      =   Subsegment::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    //dd('llego');
        return view('admin.subsegment.index',compact('subsegments'));
      
    }

    public function create()
    {

       /* $personregister         = Person::on(Auth::user()->database_name)->find($id);
        $estados                = Estado::on(Auth::user()->database_name)->orderBY('descripcion','asc')->pluck('descripcion','id')->toArray();
        $municipios             = Municipio::on(Auth::user()->database_name)->orderBY('descripcion','asc')->pluck('descripcion','id')->toArray();
       */ $segments                  = Segment::on(Auth::user()->database_name)->orderBy('description', 'asc')->get();

        return view('admin.subsegment.create',compact('segments'));
    }

    public function store(Request $request)
    {
        //
        $data = request()->validate([
            'description'         =>'required|max:255',
            'status'         =>'required|max:1',
            'segment_id'         =>'required',
            
        ]);

        $users = new Subsegment();
        $users->setConnection(Auth::user()->database_name);

        $users->description = request('description');
        $users->segment_id = request('segment_id');
        $users->status = request('status');

        $users->save();
        return redirect('/subsegment')->withSuccess('Registro Exitoso!');
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

        $var  = Subsegment::on(Auth::user()->database_name)->find($id);
        $segments        = Segment::on(Auth::user()->database_name)->get();

        return view('admin.subsegment.edit',compact('var','segments'));
    }

   


    public function update(Request $request,$id)
    {
        $subsegment =  Subsegment::on(Auth::user()->database_name)->find($id);
       
        $subsegment_status = $subsegment->status;

        $request->validate([
            'description'         =>'required|max:255',
            'segment_id'  => 'required|integer',
            
        ]);//verifica que el usuario existe

        
        $var = Subsegment::on(Auth::user()->database_name)->findOrFail($id);
        $var->description         = request('description');
        $var->segment_id       = request('segment_id');
      
       
        if(request('status') == null){
            $var->status = $subsegment_status;
        }else{
            $var->status = request('status');
        }
       

        $var->save();


        return redirect('/subsegment')->withSuccess('Registro Guardado Exitoso!');

    }


}