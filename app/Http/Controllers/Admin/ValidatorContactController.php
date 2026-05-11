<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValidatorContact;
use Illuminate\Http\Request;

class ValidatorContactController extends Controller
{
    public function index()
    {
        $contacts = ValidatorContact::latest()->paginate(10);
        return view('admin.validator_contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.validator_contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        ValidatorContact::create($request->all());

        return redirect()->route('admin.validator-contacts.index')->with('success', 'Kontak validator berhasil ditambahkan.');
    }

    public function edit(ValidatorContact $validatorContact)
    {
        return view('admin.validator_contacts.edit', compact('validatorContact'));
    }

    public function update(Request $request, ValidatorContact $validatorContact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $validatorContact->update($request->all());

        return redirect()->route('admin.validator-contacts.index')->with('success', 'Kontak validator berhasil diperbarui.');
    }

    public function destroy(ValidatorContact $validatorContact)
    {
        $validatorContact->delete();
        return redirect()->route('admin.validator-contacts.index')->with('success', 'Kontak validator berhasil dihapus.');
    }
}
