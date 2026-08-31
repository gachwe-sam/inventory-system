<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Branch;
use PHPUnit\Event\Telemetry\System;


class SupplierController extends Controller
{
    public function index(){
        $suppliers = Supplier::orderBy("id","desc")->paginate(10);
        return view("supplier.index",compact('suppliers'));
    }
    public function create(){
      
        return view('supplier.create',compact('supplieroptions'));

    }
    public function store(Request $request){

        $validated=$this->validateSupplier($request);

        supplier::create($validated);       
       
        return redirect('supplier.index')->with('success','supplier created successfully');
        
    }
    public function show(Supplier $supplier){

        return view('supplier.show',compact('supplier'));

    }
    public function edit(Supplier $supplier){
        
        return view('supplier.edit',compact('supplier','id'));
    } 
    public function update(Request $request, $id){
        $validated=$this->validateSupplier($request);
       
        $supplier->update($validated);

        return redirect()->route('suppliers.index')
                        ->with('success','supplier updated successfully');
    }

    public function destroy(Supplier $supplier){

        $supplier->delete();
        
        return redirect('supplier.index')->with('success','supplier deleted');
    
    }
     private function validateSupplier(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'item_id' => [
                'nullable',
                'exists:item,id',
            ],
        ]);
    }
}
