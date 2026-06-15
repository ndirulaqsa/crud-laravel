<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
{
    return Item::all();
}

 public function store(Request $request)
{
    // 1. Validasi — tambahkan field photo
    $request->validate([
        'title'         => 'required|string',
        'location'      => 'required|string',
        'description'   => 'required|string',
        'status'        => 'required|in:lost,found',
        'reporter_name' => 'required|string',
        'contact'       => 'required|string',
        'reported_at'   => 'nullable|date_format:Y-m-d H:i:s',
        'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ✅ tambah ini
    ]);

    // 2. Proses upload foto
    $photoUrl = null;
    if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
        $path = $request->file('photo')->store('photos', 'public');
        $photoUrl = url('storage/' . $path); // ✅ URL lengkap untuk Flutter
    }

    // 3. Simpan ke database — manual agar photo_url ikut tersimpan
    $item = Item::create([
        'title'         => $request->title,
        'location'      => $request->location,
        'description'   => $request->description,
        'status'        => $request->status,
        'reporter_name' => $request->reporter_name,
        'contact'       => $request->contact,
        'reported_at'   => $request->reported_at,
        'photo_url'     => $photoUrl, // ✅ simpan URL foto
    ]);

    return response()->json($item, 201);
}

    public function show(string $id)
    {
        return Item::with('category')->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->all());
        return response()->json($item);
    }

    public function destroy(string $id)
    {
        Item::destroy($id);
        return response()->json(null, 204);
    }
    public function markAsFound(string $id)
{
    $item = Item::findOrFail($id);
    $item->update(['status' => 'found']);
    return response()->json($item, 200);
}
}