<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CriteriaController extends Controller
{
    private function rules(?Criteria $criteria = null): array
    {
        return [
            'kode' => ['required', 'string', 'max:10', Rule::unique('criterias', 'kode')->ignore($criteria?->id)],
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:benefit,cost',
            'sumber' => ['required', Rule::in(array_keys(Criteria::SUMBER))],
            'bobot' => 'required|numeric|min:0.01|max:1',
        ];
    }

    public function index()
    {
        $items = Criteria::orderBy('kode')->get();

        return view('criterias.index', ['items' => $items, 'total' => round((float) $items->sum('bobot'), 4)]);
    }

    public function create()
    {
        return view('criterias.form', ['item' => new Criteria]);
    }

    public function store(Request $request)
    {
        Criteria::create($request->validate($this->rules()));

        return redirect()->route('criterias.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criteria $criteria)
    {
        return view('criterias.form', ['item' => $criteria]);
    }

    public function update(Request $request, Criteria $criteria)
    {
        $criteria->update($request->validate($this->rules($criteria)));

        return redirect()->route('criterias.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criteria)
    {
        $criteria->delete();

        return redirect()->route('criterias.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
