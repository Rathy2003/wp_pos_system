<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Database\QueryException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all();
        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:stores,name|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
        ]);

        $filename = null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('stores'), $logoName);
            $filename = $logoName;
        }

        $store = Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'logo' => $filename,
            'status' => $request->status,
        ]);
        
        return redirect()->route('stores.index')->with('success', 'Store created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = Store::find($id);
        return view('admin.stores.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:stores,name,' . $id . '|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'status' => 'required|boolean',
        ]);

        $filename = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('stores'), $logoName);
            $filename = $logoName;
        }

        $store = Store::find($id);
        $oldLogo = $store->logo;
        $store->name = $request->name;
        $store->description = $request->description;
        $store->address = $request->address;
        $store->status = $request->status;
        if ($filename) {
            $store->logo = $filename;
        }
        $store->save();

        if ($oldLogo && !$filename) {
            if (file_exists(public_path('stores/' . $oldLogo))) {
                unlink(public_path('stores/' . $oldLogo));
            }
        }
        return redirect()->route('stores.index')->with('success', 'Store updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = Store::find($id);
        try {
            $oldLogo = $store->logo;
            $store->delete();
            if ($oldLogo) {
                if (file_exists(public_path('stores/' . $oldLogo))) {
                    unlink(public_path('stores/' . $oldLogo));
                }
            }
            return redirect()->route('stores.index')->with('success', 'Store deleted successfully');
        } catch (QueryException $e) {
            return redirect()->route('stores.index')->with('error', 'Cannot delete store because it is associated with a user');
        }
        
    }
}
