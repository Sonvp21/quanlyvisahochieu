<x-app-layout>
    @php $in = 'mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
    <div class="mx-auto max-w-2xl space-y-5">
        <a href="{{ route('admin.mail-rules.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">← Quy tắc email</a>
        <form method="POST" action="{{ route('admin.mail-rules.update', $rule) }}" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf @method('PUT')
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ \App\Models\MailRule::EVENTS[$rule->event] ?? $rule->event }}</h2>
                <p class="mt-0.5 text-xs text-slate-500">Sự kiện cố định, chỉ sửa được người nhận và tệp đính kèm.</p>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Tên quy tắc</label>
                <input type="text" name="name" value="{{ old('name', $rule->name) }}" class="{{ $in }}" required>
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Áp dụng cho</label>
                <select name="direction" class="{{ $in }}">
                    <option value="" @selected(!old('direction', $rule->direction))>Cả đoàn ra và đoàn vào</option>
                    <option value="out" @selected(old('direction', $rule->direction) === 'out')>Chỉ đoàn ra</option>
                    <option value="in" @selected(old('direction', $rule->direction) === 'in')>Chỉ đoàn vào</option>
                </select>
            </div>
            <fieldset>
                <legend class="text-sm font-medium text-slate-600">Gửi đến</legend>
                <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach (['to_next_approver' => 'Người duyệt bước kế tiếp', 'to_creator' => 'Người lập hồ sơ', 'to_members' => 'Các thành viên trong đoàn', 'to_unit_manager' => 'Lãnh đạo đơn vị đề xuất'] as $k => $lbl)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="{{ $k }}" value="1" @checked(old($k, $rule->$k)) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"> {{ $lbl }}
                        </label>
                    @endforeach
                </div>
                <p class="mt-4 text-sm font-medium text-slate-600">Toàn bộ tài khoản thuộc vai trò</p>
                <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach (\App\Models\User::ROLES as $k => $lbl)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="to_roles[]" value="{{ $k }}" @checked(in_array($k, old('to_roles', $rule->to_roles ?? []))) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"> {{ $lbl }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
            <div>
                <label class="text-sm font-medium text-slate-600">Email bổ sung (cách nhau bằng dấu phẩy)</label>
                <input type="text" name="extra_emails" value="{{ old('extra_emails', $rule->extra_emails) }}" placeholder="vd: hieutruong@tuaf.edu.vn" class="{{ $in }}">
            </div>
            <fieldset>
                <legend class="text-sm font-medium text-slate-600">Đính kèm biểu mẫu Word</legend>
                <div class="mt-2 flex flex-wrap gap-x-6 gap-y-2">
                    @foreach (\App\Models\MailRule::DOCS as $k => $lbl)
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="attachments[]" value="{{ $k }}" @checked(in_array($k, old('attachments', $rule->attachments ?? []))) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"> {{ $lbl }}
                        </label>
                    @endforeach
                </div>
                <p class="mt-1 text-xs text-slate-400">Biểu mẫu chỉ đính kèm khi hồ sơ có đủ dữ liệu (vd: báo cáo chỉ có sau khi đã nộp; quyết định cử đi chỉ cho đoàn ra).</p>
            </fieldset>
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" name="enabled" value="1" @checked(old('enabled', $rule->enabled)) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"> Bật quy tắc này
            </label>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('admin.mail-rules.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Hủy</a>
                <button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu</button>
            </div>
        </form>
    </div>
</x-app-layout>
