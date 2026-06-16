<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(Item::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string',
            'location'      => 'required|string',
            'description'   => 'required|string',
            'status'        => 'required|in:lost,found',
            'reporter_name' => 'required|string',
            'contact'       => 'required|string',
            'reported_at'   => 'nullable|date_format:Y-m-d H:i:s',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('photos', 'public');
            $photoUrl = url('storage/' . $path);
        }

        $item = Item::create([
            'title'         => $request->title,
            'location'      => $request->location,
            'description'   => $request->description,
            'status'        => $request->status,
            'reporter_name' => $request->reporter_name,
            'contact'       => $request->contact,
            'reported_at'   => $request->reported_at,
            'photo_url'     => $photoUrl,
        ]);

        return response()->json($item, 201);
    }

    public function show(string $id)
    {
        return response()->json(Item::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);

        // ✅ Verifikasi nomor WA
        $inputNomor = preg_replace('/\D/', '', $request->contact_verify);
        $dataNomor  = preg_replace('/\D/', '', $item->contact);

        if ($inputNomor !== $dataNomor) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WA tidak cocok. Verifikasi gagal.'
            ], 403);
        }

        $request->validate([
            'title'       => 'required|string',
            'location'    => 'required|string',
            'description' => 'required|string',
            'status'      => 'required|in:lost,found',
            'reported_at' => 'nullable|date_format:Y-m-d H:i:s',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ Update foto jika ada foto baru
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('photos', 'public');
            $item->photo_url = url('storage/' . $path);
        }

        $item->update([
            'title'       => $request->title,
            'location'    => $request->location,
            'description' => $request->description,
            'status'      => $request->status,
            'reported_at' => $request->reported_at,
            'photo_url'   => $item->photo_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diupdate!',
            'data'    => $item
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $item = Item::findOrFail($id);

        // ✅ Verifikasi nomor WA
        $inputNomor = preg_replace('/\D/', '', $request->contact_verify);
        $dataNomor  = preg_replace('/\D/', '', $item->contact);

        if ($inputNomor !== $dataNomor) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WA tidak cocok. Verifikasi gagal.'
            ], 403);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus.'
        ]);
    }

    public function markAsFound(string $id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'found']);
        return response()->json($item, 200);
    }
}
