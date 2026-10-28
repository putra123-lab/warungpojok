
<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-bold uppercase tracking-widest text-orange-600">
                WARUNG POJOK
            </p>
            <h2 class="text-2xl font-extrabold text-neutral-900">
                Manajemen Bahan Baku
            </h2>
            <p class="mt-1 text-sm text-neutral-500">
                Pantau persediaan bahan untuk operasional warung.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-orange-50/50 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Tombol navigasi -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center rounded-xl bg-neutral-900 px-5 py-3 font-bold text-white no-underline hover:bg-orange-600">
                    ← Kembali ke Dashboard
                </a>

                <a href="{{ route('ingredients.create') }}"
                   class="inline-flex items-center rounded-xl bg-orange-500 px-5 py-3 font-bold text-white no-underline hover:bg-orange-600">
                    + Tambah Bahan
                </a>
            </div>

            <!-- Pesan sukses -->
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Pesan error -->
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabel bahan baku -->
            <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm">
                <div class="border-b border-orange-100 p-6">
                    <h3 class="text-xl font-extrabold text-neutral-900">
                        Daftar Bahan Baku
                    </h3>
                    <p class="mt-1 text-sm text-neutral-500">
                        Kelola stok, satuan, dan harga modal bahan.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-orange-50">
                            <tr>
                                <th class="px-5 py-4">No.</th>
                                <th class="px-5 py-4">Nama Bahan</th>
                                <th class="px-5 py-4">Satuan</th>
                                <th class="px-5 py-4">Stok</th>
                                <th class="px-5 py-4">Stok Minimum</th>
                                <th class="px-5 py-4">Harga Modal/Satuan</th>
                                <th class="px-5 py-4">Status Stok</th>
                                <th class="px-5 py-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-orange-50">
                            @forelse ($ingredients as $ingredient)
                                <tr class="hover:bg-orange-50/60">
                                    <td class="whitespace-nowrap px-5 py-4 text-neutral-600">
                                        {{ $ingredients instanceof \Illuminate\Pagination\AbstractPaginator
                                            ? $ingredients->firstItem() + $loop->index
                                            : $loop->iteration }}
                                    </td>

                                    <td class="px-5 py-4 font-bold text-neutral-900">
                                        {{ $ingredient->name }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-neutral-600">
                                        {{ $ingredient->unit ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-neutral-900">
                                        {{ number_format((float) ($ingredient->stock ?? 0), 2, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-neutral-600">
                                        {{ number_format((float) ($ingredient->minimum_stock ?? $ingredient->min_stock ?? 0), 2, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 font-bold text-neutral-900">
                                        Rp {{ number_format((float) ($ingredient->cost_per_unit ?? $ingredient->purchase_price ?? $ingredient->price ?? 0), 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        @php
                                            $stock = (float) ($ingredient->stock ?? 0);
                                            $minimum = (float) ($ingredient->minimum_stock ?? $ingredient->min_stock ?? 0);
                                        @endphp

                                        @if ($stock <= $minimum)
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                                Stok Menipis
                                            </span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-800">
                                                Aman
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('ingredients.show', $ingredient) }}"
                                               class="rounded-lg bg-neutral-900 px-3 py-2 text-xs font-bold text-white no-underline hover:bg-neutral-700">
                                                Detail
                                            </a>

                                            <a href="{{ route('ingredients.edit', $ingredient) }}"
                                               class="rounded-lg bg-orange-500 px-3 py-2 text-xs font-bold text-white no-underline hover:bg-orange-600">
                                                Edit
                                            </a>

                                            <form action="{{ route('ingredients.destroy', $ingredient) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center">
                                        <div class="text-4xl">🥚</div>
                                        <p class="mt-3 font-bold text-neutral-800">
                                            Belum ada bahan baku.
                                        </p>
                                        <p class="mt-1 text-sm text-neutral-500">
                                            Tambahkan bahan untuk mulai mengelola persediaan.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($ingredients, 'links'))
                    <div class="border-t border-orange-100 p-5">
                        {{ $ingredients->links() }}
                    </div>
                @endif
            </div>

            <footer class="mt-6 text-center text-xs text-neutral-400">
                © {{ date('Y') }} WARUNG POJOK
            </footer>

        </div>
    </div>
</x-app-layout>
