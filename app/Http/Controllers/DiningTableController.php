<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiningTableController extends Controller
{
    public function index()
    {
        $tables = DiningTable::withCount('orders')
            ->orderBy('table_number')
            ->get();

        $totalTables = $tables->count();
        $activeTables = $tables->where('is_active', true)->count();

        return view('dining-tables.index', compact(
            'tables',
            'totalTables',
            'activeTables'
        ));
    }

    public function create()
    {
        return view('dining-tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                'unique:dining_tables,table_number',
            ],
            'name' => ['nullable', 'string', 'max:100'],
        ]);

        DiningTable::create([
            'table_number' => $validated['table_number'],
            'name' => $validated['name'] ?? null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('dining-tables.index')
            ->with('success', 'Meja berhasil ditambahkan dan QR Code dibuat.');
    }

    public function edit(DiningTable $diningTable)
    {
        return view('dining-tables.edit', compact('diningTable'));
    }

    public function update(Request $request, DiningTable $diningTable)
    {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('dining_tables', 'table_number')
                    ->ignore($diningTable->id),
            ],
            'name' => ['nullable', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
        ]);

        $diningTable->update($validated);

        return redirect()
            ->route('dining-tables.index')
            ->with('success', 'Data meja berhasil diperbarui.');
    }

    public function destroy(DiningTable $diningTable)
    {
        if ($diningTable->orders()->exists()) {
            return back()->with(
                'error',
                'Meja tidak bisa dihapus karena sudah memiliki riwayat pesanan. Nonaktifkan meja jika tidak digunakan.'
            );
        }

        $diningTable->delete();

        return redirect()
            ->route('dining-tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }

    public function print()
    {
        $tables = DiningTable::where('is_active', true)
            ->orderBy('table_number')
            ->get();

        return view('dining-tables.print', compact('tables'));
    }
}
