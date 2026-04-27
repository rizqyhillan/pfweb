<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(15);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'kontak' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'alamat' => 'nullable|string',
        ]);
        Supplier::create($v);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $v = $request->validate([
            'nama_supplier' => 'required|string|max:150',
            'kontak' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'alamat' => 'nullable|string',
        ]);
        $supplier->update($v);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
