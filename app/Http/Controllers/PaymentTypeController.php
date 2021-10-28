<?php

namespace App\Http\Controllers;

use App\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentTypeController extends Controller
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
           $paymenttypes = PaymentType::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.paymenttypes.index',compact('paymenttypes'));
      
    }

    public function create()
    {

        

        return view('admin.paymenttypes.create');
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
            'description'   =>'required|max:100',
            'type'          =>'required|max:15',
            'credit_days'   =>'required|integer',
            'pide_ref'      =>'required|max:15',
            'small_box'     =>'required|max:15',
            'nature'        =>'required|max:15',
            'point'         =>'required|max:15',


            'status'        =>'required|max:1',
            
           
        ]);

        $users = new Paymenttype();
        $users->setConnection(Auth::user()->database_name);

        $users->description = request('description');
        $users->type = request('type');
        $users->credit_days =  request('credit_days');
        $users->pide_ref = request('pide_ref');
        $users->small_box =  request('small_box');
        $users->nature =  request('nature');
        $users->point =  request('point');

        $users->status =  request('status');
       

        $users->save();

        return redirect('paymenttypes')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $var = Paymenttype::on(Auth::user()->database_name)->find($id);
        
        return view('admin.paymenttypes.edit',compact('var'));
    }

   


    public function update(Request $request,$id)
    {
       
        $vars =  Paymenttype::on(Auth::user()->database_name)->find($id);

        $var_status = $vars->status;
      

        $data = request()->validate([
           
            'description'   =>'required|max:100',
            'type'          =>'required|max:15',
            'credit_days'   =>'required|integer',
            'pide_ref'      =>'required|max:15',
            'small_box'     =>'required|max:15',
            'nature'        =>'required|max:15',
            'point'         =>'required|max:15',


            'status'        =>'required|max:1',
            
           
        ]);

        $var  = Paymenttype::on(Auth::user()->database_name)->findOrFail($id);
        $var->description        = request('description');
       
        $var->type = request('type');
        $var->credit_days =  request('credit_days');
        $var->pide_ref = request('pide_ref');
        $var->small_box =  request('small_box');
        $var->nature =  request('nature');
        $var->point =  request('point');

        if(request('status') == null){
            $var->status = $var_status;
        }else{
            $var->status = request('status');
        }
       

        $var->save();


        return redirect('paymenttypes')->withSuccess('Registro Guardado Exitoso!');

    }


}
