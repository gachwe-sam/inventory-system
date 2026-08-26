<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class purchases extends Controller
{

    public function index(){
        $purchases = purchases::orderBy("id","desc")->paginate(10);
        return view("purchase.index",compact('items','category'));
    }
    public function create(){
        $purchases = $this->purchaseoptions();
        return view('purchase.create',compact('purchaseoptions'));

    }
    public function store(Request $request){
        $validated=$this->validated($request);
        $purchase=purchase::create($validated);
        return redirect('purchase.index')->with('success','purchase created successfully');
        
    }
    public function show($id){}
    public function edit($id){
        $purchases = purchases::find($id);
        return view('purchase.edit',compact('purchase','id'));
    } 
    public function update(Request $request, $id){
        $validated=$this->validatepurchase($request);
    }

    public function destroy($id){}
    public function products(){}
    public function purchase(){}
    public function updatepurchase(Request $request, $id){}
    public function destroypurchase(Request $request, $id){}

}

}
