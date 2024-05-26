<?php

namespace App\Http\Controllers;

use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new SkillResource(true, 'List Data Posts', Skill::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $skill = Skill::create([
            'name'   => $request->name,
        ]);

        return new SkillResource(true, 'Data skill Berhasil Ditambahkan!', $skill);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $skill = Skill::findOrFail($id);
            return new SkillResource(true, 'Data skill Berhasil Ditampilkan!', $skill);
        } catch (ModelNotFoundException $e) {
            return new SkillResource(false, 'Data skill Gagal Ditampilkan!', 404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $skill = Skill::findOrFail($id);
            return new SkillResource(true, 'Data skill siap untuk diedit!', $skill);
        } catch (ModelNotFoundException $e) {
            return new SkillResource(false, 'Data skill Gagal Ditampilkan!', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $skill = Skill::findOrFail($id);
            $skill->update([
                'name' => $request->name,
            ]);
            return new SkillResource(true, 'Data skill Berhasil Diubah!', $skill);
        } catch (ModelNotFoundException $e) {
            return new SkillResource(false, 'Data skill Gagal Diubah!', null, 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $skill = Skill::findOrFail($id);
            $skill->delete();
            return new SkillResource(true, 'Data skill Berhasil Dihapus!', $skill);
        } catch (ModelNotFoundException $e) {
            return new SkillResource(false, 'Data skill Gagal Dihapus!', 404);
        }
    }

    public function bulkDelete(Request $request, $start, $end)
    {
        try {
            // Hapus data berdasarkan ID
            $start = (int)$start;
            $end = (int)$end;

            // Hapus data dalam rentang ID
            DB::transaction(function () use ($start, $end) {
                Skill::whereBetween('id', [$start, $end])->delete();
            });

            // Kembalikan response sukses
            return response()->json(['message' => 'Data skills berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Kembalikan response error jika terjadi kesalahan
            return response()->json(['message' => 'Data skills gagal dihapus', 'error' => $e->getMessage()], 500);
        }
    }
}
