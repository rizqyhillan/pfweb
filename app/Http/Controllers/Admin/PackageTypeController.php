<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageType;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class PackageTypeController extends Controller
{
    private function sectionParams(Request $request): array
    {
        return $request->only('section');
    }

    public function index(Request $request)
    {
        if (! Schema::hasTable('package_types')) {
            $packageTypes = collect();

            foreach (PackageType::defaultOptions() as $name => $label) {
                $packageTypes->push((object) [
                    'name' => $name,
                    'label' => $label,
                    'description' => null,
                ]);
            }

            return view('admin.package-types.index', compact('packageTypes'))->with('tableMissing', true);
        }

        $packageTypes = PackageType::latest()->pathPaginate(
            15,
            url('admin/package-types/page').($request->query('section') ? '?section='.urlencode($request->query('section')) : '')
        );

        return view('admin.package-types.index', compact('packageTypes'));
    }

    public function create(Request $request)
    {
        if (! Schema::hasTable('package_types')) {
            return redirect()->route('admin.package-types.index', $this->sectionParams($request))
                ->with('error', 'Tabel package_types belum dibuat. Jalankan php artisan migrate untuk menyambungkan jenis paket dasar.');
        }

        return view('admin.package-types.create');
    }

    public function store(Request $request)
    {
        if (! Schema::hasTable('package_types')) {
            return redirect()->route('admin.package-types.index', $this->sectionParams($request))
                ->with('error', 'Tabel package_types belum dibuat. Jalankan php artisan migrate.');
        }

        $v = $request->validate([
            'name' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:package_types,name'],
            'label' => ['required', 'string', 'max:100'],
            'harga_per_malam' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'fasilitas_input' => ['nullable', 'string'],
        ]);

        // Konversi input fasilitas (satu per baris) ke JSON array
        if (!empty($v['fasilitas_input'])) {
            $v['fasilitas'] = array_values(array_filter(array_map('trim', explode("\n", $v['fasilitas_input']))));
        } else {
            $v['fasilitas'] = [];
        }
        unset($v['fasilitas_input']);

        PackageType::create($v);

        return redirect()->route('admin.package-types.index', $this->sectionParams($request))->with('success', 'Jenis paket berhasil ditambahkan.');
    }

    public function show(PackageType $packageType, Request $request)
    {
        return redirect()->route('admin.package-types.index', $this->sectionParams($request));
    }

    public function edit(PackageType $packageType, Request $request)
    {
        return view('admin.package-types.edit', compact('packageType'));
    }

    public function update(Request $request, PackageType $packageType)
    {
        if (! Schema::hasTable('package_types')) {
            return redirect()->route('admin.package-types.index', $this->sectionParams($request))
                ->with('error', 'Tabel package_types belum dibuat. Jalankan php artisan migrate.');
        }

        $v = $request->validate([
            'name' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('package_types', 'name')->ignore($packageType->id)],
            'label' => ['required', 'string', 'max:100'],
            'harga_per_malam' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'fasilitas_input' => ['nullable', 'string'],
        ]);

        // Konversi input fasilitas (satu per baris) ke JSON array
        if (!empty($v['fasilitas_input'])) {
            $v['fasilitas'] = array_values(array_filter(array_map('trim', explode("\n", $v['fasilitas_input']))));
        } else {
            $v['fasilitas'] = [];
        }
        unset($v['fasilitas_input']);

        $packageType->update($v);

        return redirect()->route('admin.package-types.index', $this->sectionParams($request))->with('success', 'Jenis paket berhasil diperbarui.');
    }

    public function destroy(PackageType $packageType, Request $request)
    {
        if (! Schema::hasTable('package_types')) {
            return redirect()->route('admin.package-types.index', $this->sectionParams($request))
                ->with('error', 'Tabel package_types belum dibuat. Jalankan php artisan migrate.');
        }

        $usedRooms = Room::where('paket', $packageType->name)->count();

        if ($usedRooms > 0) {
            return redirect()->route('admin.package-types.index', $this->sectionParams($request))
                ->with('error', "Jenis paket '{$packageType->label}' tidak dapat dihapus karena masih digunakan oleh {$usedRooms} kamar.");
        }

        $packageType->delete();

        return redirect()->route('admin.package-types.index', $this->sectionParams($request))->with('success', 'Jenis paket berhasil dihapus.');
    }
}
