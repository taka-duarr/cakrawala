<?php

namespace App\Http\Controllers;

use App\Models\SchoolLocation;
use Illuminate\Http\Request;

class SchoolLocationController extends Controller
{
    public function index()
    {
        $locations = SchoolLocation::latest()->get();
        return view('admin.school_locations', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:5|max:1000',
        ]);

        SchoolLocation::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        return redirect()->back()->with('success', 'Lokasi sekolah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:5|max:1000',
        ]);

        $location = SchoolLocation::findOrFail($id);
        $location->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Lokasi sekolah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $location = SchoolLocation::findOrFail($id);
        $location->delete();

        return redirect()->back()->with('success', 'Lokasi sekolah berhasil dihapus!');
    }
}
