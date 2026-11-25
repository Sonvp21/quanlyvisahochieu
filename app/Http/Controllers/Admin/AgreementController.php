<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        $q = Agreement::with(['partner', 'unit']);
        $filter = $request->get('state');
        if ($filter === 'expiring') {
            $q->expiringSoon()->whereDate('expires_at', '>=', today());
        } elseif ($filter === 'expired') {
            $q->where('is_terminated', false)->whereDate('expires_at', '<', today());
        } elseif ($filter === 'active') {
            $q->where('is_terminated', false)->where(fn ($w) => $w->whereNull('expires_at')->orWhereDate('expires_at', '>=', today()));
        }

        return view('admin.partners.agreements', [
            'agreements' => $q->orderByRaw('expires_at asc nulls last')->paginate(25)->withQueryString(),
            'counts' => [
                'expiring' => Agreement::expiringSoon()->whereDate('expires_at', '>=', today())->count(),
                'expired'  => Agreement::where('is_terminated', false)->whereDate('expires_at', '<', today())->count(),
            ],
        ]);
    }

    public function store(Request $request, Partner $partner)
    {
        $d = $request->validate([
            'agreement_type' => ['required', Rule::in(array_keys(Agreement::TYPES))],
            'title'          => 'required|string|max:255',
            'reference_no'   => 'nullable|string|max:100',
            'unit_id'        => 'nullable|exists:units,id',
            'signed_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:signed_at',
            'scope'          => 'nullable|string|max:3000',
            'file'           => 'nullable|file|max:15360|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $file = $request->file('file');
        unset($d['file']);
        if ($file) {
            $d['file_path'] = $file->store('agreements');
            $d['file_name'] = $file->getClientOriginalName();
        }
        $partner->agreements()->create($d);

        return redirect()->route('admin.partners.show', $partner)->with('success', 'Đã thêm thỏa thuận.');
    }

    public function terminate(Agreement $agreement)
    {
        $agreement->update(['is_terminated' => !$agreement->is_terminated]);

        return back()->with('success', $agreement->is_terminated ? 'Đã đánh dấu chấm dứt.' : 'Đã khôi phục hiệu lực.');
    }

    public function destroy(Agreement $agreement)
    {
        if ($agreement->file_path) {
            Storage::delete($agreement->file_path);
        }
        $agreement->delete();

        return back()->with('success', 'Đã xóa thỏa thuận.');
    }

    public function download(Agreement $agreement)
    {
        abort_unless($agreement->file_path && Storage::exists($agreement->file_path), 404);

        return Storage::download($agreement->file_path, $agreement->file_name);
    }
}
