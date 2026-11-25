<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        {{-- Đầu trang --}}
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Đoàn ra &amp; đoàn vào
                </a>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-bold text-slate-900">{{ $trip->title }}</h2>
                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $trip->status_class }}">{{ $trip->status_label }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500"><span class="font-mono">{{ $trip->code }}</span> · {{ $trip->direction_label }} · {{ $trip->type_label }} · tạo bởi {{ $trip->creator?->name }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @if ($canExport)
                    <a href="{{ route('trips.export.word', $trip) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Công văn báo đoàn
                    </a>
                    <a href="{{ route('trips.export.excel', $trip) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                        Phụ lục Excel
                    </a>
                    @if ($trip->delegation && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.delegations.edit', $trip->delegation) }}" class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Sửa công văn</a>
                    @endif
                @endif
                @if ($canEdit)
                    <a href="{{ route('trips.edit', $trip) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Sửa hồ sơ</a>
                    <form method="POST" action="{{ route('trips.submit', $trip) }}">
                        @csrf
                        <button class="rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-800">Gửi duyệt</button>
                    </form>
                @endif
                @if ($canReport)
                    <a href="{{ route('trips.report', $trip) }}" class="rounded-lg bg-emerald-600 px-3.5 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700">
                        {{ $trip->report ? 'Sửa báo cáo' : ($trip->isIncoming() ? 'Nộp báo cáo tiếp đoàn' : 'Nộp báo cáo sau chuyến') }}
                    </a>
                @endif
                @if ($trip->status === 'draft' && ($canEdit))
                    <form method="POST" action="{{ route('trips.destroy', $trip) }}" onsubmit="return confirm('Xóa dự thảo này?')">
                        @csrf @method('DELETE')
                        <button class="rounded-lg border border-red-200 bg-white px-3.5 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Xóa</button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Hồ sơ được trả lại --}}
        @if ($trip->status === 'returned' && ($last = $trip->approvals->where('action', 'returned')->last()))
            <div class="rounded-xl border border-orange-200 bg-orange-50 px-5 py-4 text-sm text-orange-800">
                <p class="font-semibold">Hồ sơ được trả lại để bổ sung</p>
                <p class="mt-1">{{ $last->comment }}</p>
            </div>
        @endif

        {{-- Tiến trình phê duyệt --}}
        @php
            $stepLabels = config('trips.step_labels');
            $pending = $trip->pendingStep();
            $lastSubmitId = $trip->approvals->where('action', 'submitted')->last()?->id ?? 0;
            $approvedSteps = $trip->approvals->where('action', 'approved')->where('id', '>', $lastSubmitId)->pluck('step')->all();
        @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-900">Tiến trình phê duyệt</h3>
            <ol class="mt-4 flex flex-wrap items-center gap-y-3">
                @foreach ($flow as $i => $step)
                    @php
                        $done = in_array($step, $approvedSteps, true);
                        $current = $pending === $step;
                    @endphp
                    <li class="flex items-center">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold
                            {{ $done ? 'bg-emerald-500 text-white' : ($current ? 'bg-amber-400 text-white' : 'bg-slate-200 text-slate-500') }}">
                            @if ($done) ✓ @else {{ $i + 1 }} @endif
                        </span>
                        <span class="ml-2 text-sm {{ $current ? 'font-semibold text-slate-900' : 'text-slate-600' }}">{{ $stepLabels[$step] ?? $step }}</span>
                        @unless ($loop->last)<span class="mx-3 hidden h-px w-8 bg-slate-200 sm:block"></span>@endunless
                    </li>
                @endforeach
                @if (empty($flow))
                    <li class="text-sm text-slate-500">Không có bước phê duyệt cho hồ sơ này.</li>
                @endif
            </ol>

            {{-- Khung ý kiến của người duyệt --}}
            @if ($canAct)
                <form method="POST" action="{{ route('trips.decide', $trip) }}" class="mt-5 rounded-xl border border-amber-200 bg-amber-50/60 p-4">
                    @csrf
                    <p class="text-sm font-semibold text-slate-900">Ý kiến của bạn ({{ $stepLabels[$pending] ?? $pending }})</p>
                    <textarea name="comment" rows="2" placeholder="Nhận xét (bắt buộc khi trả lại hoặc không duyệt)"
                              class="mt-2 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">{{ old('comment') }}</textarea>
                    @error('comment')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button name="action" value="approve" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Đồng ý</button>
                        <button name="action" value="return" class="rounded-lg border border-orange-300 bg-white px-4 py-2 text-sm font-medium text-orange-700 hover:bg-orange-50">Trả lại bổ sung</button>
                        <button name="action" value="reject" class="rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Không duyệt</button>
                    </div>
                </form>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                {{-- Thông tin --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">Thông tin chuyến đi</h3>
                    <dl class="mt-5 grid grid-cols-1 gap-x-8 gap-y-5 text-sm md:grid-cols-2">
                        <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $trip->isIncoming() ? 'Quốc gia / vùng lãnh thổ' : 'Nơi đến' }}</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $trip->country }}@if($trip->city), {{ $trip->city }}@endif</dd></div>
                        <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Thời gian</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $trip->time_range }} <span class="font-normal text-slate-400">({{ $trip->days }} ngày)</span></dd></div>
                        <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $trip->isIncoming() ? 'Đơn vị tiếp đón' : 'Đơn vị đề xuất' }}</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $trip->unit?->name ?? '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $trip->isIncoming() ? 'Tổ chức cử đoàn' : 'Đơn vị mời / tiếp nhận' }}</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $trip->host_org ?: '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Kinh phí dự toán</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $trip->budget_estimate !== null ? number_format($trip->budget_estimate, 0, ',', '.') . ' đ' : '—' }}</dd></div>
                        <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nguồn kinh phí</dt>
                            <dd class="mt-1 font-medium text-slate-800">{{ $trip->funding_source ?: '—' }}</dd></div>
                        @if ($trip->isIncoming())
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Địa điểm làm việc</dt>
                                <dd class="mt-1 font-medium text-slate-800">{{ $trip->work_location ?: '—' }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Nơi lưu trú</dt>
                                <dd class="mt-1 font-medium text-slate-800">{{ $trip->accommodation ?: '—' }}</dd></div>
                        @endif
                        <div class="md:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Mục đích, nội dung</dt>
                            <dd class="mt-1 whitespace-pre-line text-slate-700">{{ $trip->purpose ?: '—' }}</dd></div>
                        @if ($trip->isIncoming() && $trip->reception_notes)
                            <div class="md:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Chương trình, đón tiếp</dt>
                                <dd class="mt-1 whitespace-pre-line text-slate-700">{{ $trip->reception_notes }}</dd></div>
                        @endif
                        @if ($trip->notes)
                            <div class="md:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Ghi chú</dt>
                                <dd class="mt-1 text-slate-700">{{ $trip->notes }}</dd></div>
                        @endif
                    </dl>
                </div>

                {{-- Thành viên --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h3 class="text-base font-semibold text-slate-900">{{ $trip->isIncoming() ? 'Thành viên đoàn khách' : 'Thành viên đi' }}</h3>
                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $trip->members->count() }} người</span>
                    </div>
                    <table class="min-w-full text-sm">
                        <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Họ và tên</th><th class="px-5 py-3">{{ $trip->isIncoming() ? 'Cơ quan' : 'Đơn vị' }}</th><th class="px-5 py-3">Vai trò</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($trip->members as $m)
                                <tr>
                                    <td class="px-5 py-3 font-medium text-slate-900">{{ trim(($m->academic_title ? $m->academic_title . '. ' : '') . $m->full_name) }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $trip->isIncoming() ? ($m->organization ?: ($m->unit?->name ?? '—')) : ($m->unit?->name ?? ($m->organization ?: '—')) }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $m->pivot->role_in_trip }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Kinh phí --}}
                @php
                    $plan = $expenses->where('kind', 'plan')->sum('amount');
                    $actual = $expenses->where('kind', 'actual')->sum('amount');
                    $fmt = fn ($n) => number_format($n, 0, ',', '.') . ' đ';
                @endphp
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h3 class="text-base font-semibold text-slate-900">Kinh phí</h3>
                        <p class="text-sm text-slate-500">
                            Dự toán <span class="font-semibold text-slate-800">{{ $fmt($plan ?: ($trip->budget_estimate ?? 0)) }}</span>
                            · Thực chi <span class="font-semibold {{ $plan && $actual > $plan ? 'text-red-600' : 'text-slate-800' }}">{{ $fmt($actual) }}</span>
                        </p>
                    </div>
                    @if ($expenses->isNotEmpty())
                        <table class="mt-4 min-w-full text-sm">
                            <thead><tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                <th class="py-2 pr-3">Khoản mục</th><th class="py-2 pr-3">Loại</th><th class="py-2 pr-3 text-right">Số tiền</th><th class="py-2"></th>
                            </tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($expenses as $e)
                                    <tr>
                                        <td class="py-2 pr-3 text-slate-800">{{ \App\Models\TripExpense::CATEGORIES[$e->category] ?? $e->category }}
                                            @if ($e->description || $e->funding_source)<span class="block text-xs text-slate-400">{{ collect([$e->description, $e->funding_source ? 'Nguồn: ' . $e->funding_source : null])->filter()->implode(' · ') }}</span>@endif</td>
                                        <td class="py-2 pr-3 text-slate-600">{{ \App\Models\TripExpense::KINDS[$e->kind] ?? $e->kind }}</td>
                                        <td class="whitespace-nowrap py-2 pr-3 text-right font-medium text-slate-800">{{ $fmt($e->amount) }}</td>
                                        <td class="py-2 text-right">
                                            @if ($canFiles)
                                                <form method="POST" action="{{ route('trips.expenses.destroy', $e) }}" onsubmit="return confirm('Xóa khoản chi này?')">@csrf @method('DELETE')<button class="text-xs text-red-600 hover:text-red-700">Xóa</button></form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="mt-3 text-sm text-slate-400">Chưa ghi khoản nào.</p>
                    @endif
                    @if ($canFiles)
                        <details class="mt-4" @if ($errors->has('amount') || $errors->has('category')) open @endif>
                            <summary class="cursor-pointer text-sm font-medium text-blue-700 hover:text-blue-800">+ Ghi khoản dự toán / thực chi</summary>
                            <form method="POST" action="{{ route('trips.expenses.store', $trip) }}" class="mt-3 grid grid-cols-1 gap-3 rounded-xl border border-blue-100 bg-blue-50/40 p-4 md:grid-cols-3">
                                @csrf
                                <select name="category" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">@foreach (\App\Models\TripExpense::CATEGORIES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select>
                                <select name="kind" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">@foreach (\App\Models\TripExpense::KINDS as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach</select>
                                <div><input type="number" min="0" name="amount" placeholder="Số tiền (đồng) *" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">@error('amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <input type="text" name="description" placeholder="Diễn giải" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600 md:col-span-2">
                                <input type="text" name="funding_source" placeholder="Nguồn kinh phí" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                                <div class="md:col-span-3"><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Lưu khoản chi</button></div>
                            </form>
                        </details>
                    @endif
                </div>

                {{-- Báo cáo sau chuyến --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900">{{ $trip->isIncoming() ? 'Báo cáo kết quả tiếp đoàn' : 'Báo cáo sau chuyến' }}</h3>
                    @if ($trip->report)
                        <dl class="mt-4 space-y-4 text-sm">
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Tóm tắt nội dung đã thực hiện</dt>
                                <dd class="mt-1 whitespace-pre-line text-slate-700">{{ $trip->report->summary }}</dd></div>
                            @if ($trip->report->results)
                                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Kết quả đạt được</dt>
                                    <dd class="mt-1 whitespace-pre-line text-slate-700">{{ $trip->report->results }}</dd></div>
                            @endif
                            @if ($trip->report->recommendations)
                                <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Đề xuất, kiến nghị</dt>
                                    <dd class="mt-1 whitespace-pre-line text-slate-700">{{ $trip->report->recommendations }}</dd></div>
                            @endif
                            <p class="text-xs text-slate-400">Nộp bởi {{ $trip->report->submitter?->name }} lúc {{ $trip->report->submitted_at->format('H:i d/m/Y') }}</p>
                        </dl>
                    @elseif ($trip->status === 'approved')
                        <p class="mt-3 text-sm text-slate-500">
                            Chưa nộp báo cáo@if($trip->report_due_at), hạn nộp <span class="font-medium {{ $trip->report_due_at->isPast() ? 'text-red-600' : 'text-slate-700' }}">{{ $trip->report_due_at->format('d/m/Y') }}</span>@endif.
                        </p>
                    @else
                        <p class="mt-3 text-sm text-slate-400">Báo cáo được nộp sau khi hồ sơ được duyệt và chuyến đi kết thúc.</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                {{-- Biểu mẫu Word --}}
                @if (!empty($docs))
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-900">Biểu mẫu Word</h3>
                        <ul class="mt-3 space-y-1.5">
                            @foreach ($docs as $key => $label)
                                <li>
                                    <a href="{{ route('trips.document', [$trip, $key]) }}" class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                        <span>{{ $label }}</span>
                                        <span class="text-xs font-medium text-blue-700">Tải .docx</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <p class="mt-2 text-xs text-slate-400">Bản tạm, điền sẵn từ hồ sơ. Số văn bản và chữ ký bổ sung sau khi tải.</p>
                    </div>
                @endif

                @if ($mailLogs->isNotEmpty())
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-900">Email đã gửi</h3>
                        <ul class="mt-3 space-y-3">
                            @foreach ($mailLogs as $l)
                                <li class="text-xs">
                                    <p class="font-medium text-slate-700">{{ $l->subject }}</p>
                                    <p class="mt-0.5 break-words text-slate-500">{{ $l->recipients }}</p>
                                    <p class="mt-0.5 {{ $l->status === 'sent' ? 'text-emerald-600' : 'text-red-600' }}">{{ $l->status === 'sent' ? 'Đã gửi' : 'Lỗi: ' . $l->error }} · {{ $l->created_at->format('H:i d/m/Y') }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Lịch sử phê duyệt --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900">Lịch sử xử lý</h3>
                    <ul class="mt-4 space-y-4">
                        @php
                            $actions = ['submitted' => ['Gửi duyệt', 'bg-blue-500'], 'approved' => ['Đồng ý', 'bg-emerald-500'], 'returned' => ['Trả lại', 'bg-orange-500'], 'rejected' => ['Không duyệt', 'bg-red-500']];
                        @endphp
                        @forelse ($trip->approvals as $a)
                            <li class="flex gap-3">
                                <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $actions[$a->action][1] ?? 'bg-slate-400' }}"></span>
                                <div class="min-w-0 text-sm">
                                    <p class="font-medium text-slate-800">
                                        {{ $actions[$a->action][0] ?? $a->action }}
                                        @if ($a->step)<span class="font-normal text-slate-500">· {{ $stepLabels[$a->step] ?? $a->step }}</span>@endif
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $a->approver?->name }} · {{ $a->created_at->format('H:i d/m/Y') }}</p>
                                    @if ($a->comment)<p class="mt-1 text-slate-600">{{ $a->comment }}</p>@endif
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-slate-400">Chưa gửi duyệt.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Tệp đính kèm --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-900">Tài liệu đính kèm</h3>
                    <ul class="mt-3 space-y-2">
                        @forelse ($trip->files as $f)
                            <li class="flex items-center justify-between gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm">
                                <a href="{{ route('trips.files.download', $f) }}" class="min-w-0 flex-1">
                                    <span class="block truncate font-medium text-slate-800 hover:text-blue-700">{{ $f->original_name }}</span>
                                    <span class="text-xs text-slate-400">{{ $f->kind_label }}</span>
                                </a>
                                @if ($canFiles)
                                    <form method="POST" action="{{ route('trips.files.delete', $f) }}" onsubmit="return confirm('Xóa tệp này?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded p-1 text-slate-400 hover:text-red-600" title="Xóa">✕</button>
                                    </form>
                                @endif
                            </li>
                        @empty
                            <li class="text-sm text-slate-400">Chưa có tài liệu.</li>
                        @endforelse
                    </ul>

                    @if ($canFiles)
                        <form method="POST" action="{{ route('trips.files.upload', $trip) }}" enctype="multipart/form-data" class="mt-4 space-y-2 border-t border-slate-100 pt-4">
                            @csrf
                            <select name="kind" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                                @foreach (\App\Models\Trip::FILE_KINDS as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                            </select>
                            <input type="file" name="file" required class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-blue-700">
                            @error('file')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            <button class="w-full rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-white hover:bg-slate-900">Tải lên</button>
                            <p class="text-[11px] text-slate-400">PDF, Word, Excel, ảnh, zip — tối đa 10 MB.</p>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
