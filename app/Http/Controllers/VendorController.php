<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    //
    public function create()
    {
        return view('create-vendor');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // Ini nyimpan vendor baru
        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect()->back()->with('success', 'Vendor berhasil ditambahkan');
    }

    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);
        $search = $request->input('search', '');

        $query = Vendor::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $vendors = $query->paginate($entries);

        return view('vendor-list', compact('vendors', 'entries', 'search'));
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendorName = $vendor->name;
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor ' . $vendorName . ' berhasil dihapus');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);

        return view('vendor-edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->name = $request->name;
        $vendor->email = $request->email;
        $vendor->save();

        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil diubah');
    }
}