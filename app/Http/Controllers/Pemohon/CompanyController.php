<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;

        if (! $company) {
            return redirect()->route('pemohon.company.create');
        }

        return view('pemohon.company.index', compact('company'));
    }

    public function create()
    {
        if (Auth::user()->company) {
            return redirect()->route('pemohon.company.index');
        }

        return view('pemohon.company.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCompany($request, null, true);

        $validated['akta_pendirian']      = $this->uploadFile($request, 'akta_pendirian');
        $validated['ktp_penanggung_jawab'] = $this->uploadFile($request, 'ktp_penanggung_jawab');
        $validated['surat_kuasa']          = $this->uploadFile($request, 'surat_kuasa');
        $validated['user_id'] = Auth::id();
        $validated['status']  = 'draft';

        Company::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Data perusahaan berhasil disimpan sebagai draft.',
        ]);
    }

    public function edit()
    {
        $company = Auth::user()->company;

        abort_if(! $company, 404);
        abort_if($company->status === 'verified', 403, 'Data yang sudah terverifikasi tidak bisa diubah.');

        return view('pemohon.company.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Auth::user()->company;

        abort_if(! $company, 404);
        abort_if($company->status === 'verified', 403, 'Data yang sudah terverifikasi tidak bisa diubah.');

        $validated = $this->validateCompany($request, $company->id, false);

        if ($request->hasFile('akta_pendirian')) {
            $validated['akta_pendirian'] = $this->uploadFile($request, 'akta_pendirian', $company->akta_pendirian);
        }
        if ($request->hasFile('ktp_penanggung_jawab')) {
            $validated['ktp_penanggung_jawab'] = $this->uploadFile($request, 'ktp_penanggung_jawab', $company->ktp_penanggung_jawab);
        }
        if ($request->hasFile('surat_kuasa')) {
            $validated['surat_kuasa'] = $this->uploadFile($request, 'surat_kuasa', $company->surat_kuasa);
        }

        if ($company->status === 'rejected') {
            $validated['status'] = 'draft';
            $validated['catatan_verifikasi'] = null;
        }

        $company->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Data perusahaan berhasil diperbarui.',
        ]);
    }

    public function submitVerifikasi()
    {
        $company = Auth::user()->company;

        abort_if(! $company, 404);

        if (! in_array($company->status, ['draft', 'rejected'])) {
            return back()->with('toast_error', 'Data ini tidak bisa diajukan ulang.');
        }

        $company->update([
            'status' => 'pending',
            'catatan_verifikasi' => null,
        ]);

        return back()->with('toast_success', 'Data perusahaan berhasil diajukan untuk verifikasi.');
    }

    private function validateCompany(Request $request, $ignoreId = null, bool $filesRequired = true)
    {
        $fileRule = $filesRequired ? 'required' : 'nullable';

        return $request->validate([
            'nama_perusahaan'          => ['required', 'string', 'max:255'],
            'bentuk_badan_usaha'       => ['required', 'in:PT,CV,UD,Koperasi,Yayasan,Perorangan,Lainnya'],
            'npwp_perusahaan'          => ['required', 'string', 'max:20', 'unique:companies,npwp_perusahaan,' . $ignoreId],
            'nib'                      => ['required', 'string', 'max:15', 'unique:companies,nib,' . $ignoreId],
            'alamat_perusahaan'        => ['required', 'string'],
            'telepon_perusahaan'       => ['required', 'string', 'max:20'],
            'email_perusahaan'         => ['nullable', 'email'],
            'nama_penanggung_jawab'    => ['required', 'string', 'max:255'],
            'jabatan_penanggung_jawab' => ['required', 'string', 'max:255'],
            'telepon_penanggung_jawab' => ['required', 'string', 'max:20'],
            'nama_kuasa'               => ['nullable', 'string', 'max:255'],
            'jabatan_kuasa'            => ['nullable', 'string', 'max:255'],
            'akta_pendirian'           => [$fileRule, 'file', 'mimes:pdf', 'max:2048'],
            'ktp_penanggung_jawab'     => [$fileRule, 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'surat_kuasa'              => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ]);
    }

    private function uploadFile(Request $request, string $field, ?string $oldPath = null): ?string
    {
        if (! $request->hasFile($field)) {
            return $oldPath;
        }

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $request->file($field)->store('companies/' . $field, 'public');
    }
}
