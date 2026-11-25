<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailLog;
use App\Models\MailRule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MailRuleController extends Controller
{
    public function index()
    {
        return view('admin.mail.index', ['rules' => MailRule::orderBy('id')->get()]);
    }

    public function edit(MailRule $mailRule)
    {
        return view('admin.mail.edit', ['rule' => $mailRule]);
    }

    public function update(Request $request, MailRule $mailRule)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'direction'     => ['nullable', Rule::in(['in', 'out'])],
            'to_roles'      => 'nullable|array',
            'to_roles.*'    => Rule::in(array_keys(User::ROLES)),
            'extra_emails'  => 'nullable|string|max:1000',
            'attachments'   => 'nullable|array',
            'attachments.*' => Rule::in(array_keys(MailRule::DOCS)),
        ]);

        $mailRule->update([
            'name'             => $data['name'],
            'direction'        => $data['direction'] ?: null,
            'to_next_approver' => $request->boolean('to_next_approver'),
            'to_creator'       => $request->boolean('to_creator'),
            'to_members'       => $request->boolean('to_members'),
            'to_unit_manager'  => $request->boolean('to_unit_manager'),
            'to_roles'         => array_values($data['to_roles'] ?? []),
            'extra_emails'     => $data['extra_emails'] ?? null,
            'attachments'      => array_values($data['attachments'] ?? []),
            'enabled'          => $request->boolean('enabled'),
        ]);

        return redirect()->route('admin.mail-rules.index')->with('success', 'Đã lưu quy tắc email.');
    }

    public function toggle(MailRule $mailRule)
    {
        $mailRule->update(['enabled' => !$mailRule->enabled]);

        return back()->with('success', $mailRule->enabled ? 'Đã bật quy tắc.' : 'Đã tắt quy tắc.');
    }

    public function logs()
    {
        return view('admin.mail.logs', ['logs' => MailLog::with('trip')->latest('id')->paginate(30)]);
    }
}
