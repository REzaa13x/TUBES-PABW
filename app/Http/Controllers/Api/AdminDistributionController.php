<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DistributionReport;
use App\Models\ValidatorContact;
use Illuminate\Http\Request;

class AdminDistributionController extends Controller
{
    // --- Laporan Penyaluran API ---
    public function indexReports()
    {
        $reports = DistributionReport::with('campaign')->latest()->paginate(10);
        return response()->json($reports);
    }

    public function showReport($id)
    {
        $report = DistributionReport::with('campaign')->findOrFail($id);
        return response()->json($report);
    }

    public function updateReport(Request $request, $id)
    {
        $report = DistributionReport::findOrFail($id);
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'admin_note' => 'nullable|string'
        ]);

        $report->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        return response()->json(['message' => 'Status laporan berhasil diperbarui', 'data' => $report]);
    }

    // --- Kontak Validator API ---
    public function indexContacts()
    {
        return response()->json(ValidatorContact::all());
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $contact = ValidatorContact::create($request->all());
        return response()->json(['message' => 'Kontak berhasil ditambah', 'data' => $contact]);
    }

    public function updateContact(Request $request, $id)
    {
        $contact = ValidatorContact::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $contact->update($request->all());
        return response()->json(['message' => 'Kontak berhasil diperbarui', 'data' => $contact]);
    }

    public function destroyContact($id)
    {
        ValidatorContact::findOrFail($id)->delete();
        return response()->json(['message' => 'Kontak berhasil dihapus']);
    }
}
