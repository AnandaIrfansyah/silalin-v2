<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyVerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search', '');

        $companies = Company::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('nama_perusahaan', 'like', "%{$search}%")
                        ->orWhere('npwp_perusahaan', 'like', "%{$search}%")
                        ->orWhere('nib', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.verifikasi-perusahaan.index', compact('companies', 'status', 'search'));
    }

    public function show($id)
    {
        $company = Company::with('user')->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => [
                'id'                       => $company->id,
                'nama_perusahaan'          => $company->nama_perusahaan,
                'bentuk_badan_usaha'       => $company->bentuk_badan_usaha,
                'npwp_perusahaan'          => $company->npwp_perusahaan,
                'nib'                      => $company->nib,
                'alamat_perusahaan'        => $company->alamat_perusahaan,
                'telepon_perusahaan'       => $company->telepon_perusahaan,
                'email_perusahaan'         => $company->email_perusahaan,
                'nama_penanggung_jawab'    => $company->nama_penanggung_jawab,
                'jabatan_penanggung_jawab' => $company->jabatan_penanggung_jawab,
                'telepon_penanggung_jawab' => $company->telepon_penanggung_jawab,
                'nama_kuasa'               => $company->nama_kuasa,
                'jabatan_kuasa'            => $company->jabatan_kuasa,
                'status'                   => $company->status,
                'catatan_verifikasi'       => $company->catatan_verifikasi,
                'akta_pendirian_url'       => $company->akta_pendirian ? asset('storage/' . $company->akta_pendirian) : null,
                'ktp_penanggung_jawab_url' => $company->ktp_penanggung_jawab ? asset('storage/' . $company->ktp_penanggung_jawab) : null,
                'surat_kuasa_url'          => $company->surat_kuasa ? asset('storage/' . $company->surat_kuasa) : null,
                'pemohon_name'             => $company->user->name,
                'pemohon_email'            => $company->user->email,
            ],
        ]);
    }

    public function approve($id)
    {
        $company = Company::findOrFail($id);

        if ($company->status !== 'pending') {
            return response()->json([
                'status'  => false,
                'message' => 'Hanya data berstatus pending yang bisa disetujui.',
            ], 422);
        }

        $company->update([
            'status'             => 'verified',
            'catatan_verifikasi' => null,
            'verified_by'        => Auth::id(),
            'verified_at'        => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Perusahaan berhasil diverifikasi.',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan_verifikasi' => ['required', 'string', 'max:1000'],
        ]);

        $company = Company::findOrFail($id);

        if ($company->status !== 'pending') {
            return response()->json([
                'status'  => false,
                'message' => 'Hanya data berstatus pending yang bisa ditolak.',
            ], 422);
        }

        $company->update([
            'status'             => 'rejected',
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
            'verified_by'        => Auth::id(),
            'verified_at'        => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Perusahaan ditolak, pemohon akan diminta merevisi.',
        ]);
    }
}
