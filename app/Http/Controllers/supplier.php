<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Branch;
use PHPUnit\Event\Telemetry\System;


class supplier extends Controller
{
    public function index(){
        $suppliers = Supplier::orderBy("id","desc")->paginate(10);
        return view("supplier.index",compact('suppliers'));
    }
    public function create(){
        $suppliers = $this->supplieroptions();
        return view('supplier.create',compact('supplieroptions'));

    }
    public function store(Request $request){
        $validated=$this->validated($request);

        $trashed=$validated[''];
       
        return redirect('supplier.index')->with('success','supplier created successfully');
        
    }
    public function show(Supplier $supplier){

    }
    public function edit(Supplier $supplier){
        $suppliers = Suppliers::find($id);
        return view('supplier.edit',compact('supplier','id'));
    } 
    public function update(Request $request, $id){
        $validated=$this->validateSupplier($request);
        $suppliers = Supplier::findorfail($id);
        $suppliers->update($validated);

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
            'item_id' => [
                'required',
                'exists:item,id',
                function ($attribute, $value, $fail) {
                    $item = Item::find($value);

                    if (!$item) {
                        $fail("\"{$item->name}\" you don't have that item in your catalogue please register it ");
                    }
                },
            ],            
        ]);
    }
}
