{{--
    Partial dùng chung cho create.blade.php và edit.blade.php.
    Biến truyền vào: $delegation (model, mới hoặc đã có), $isEdit (bool)
--}}
<div x-data="delegationForm({{ $isEdit ? $delegation->members->map(fn($m) => [
        'id' => $m->id,
        'full_name' => $m->full_name,
        'gender' => $m->gender,
        'date_of_birth' => $m->date_of_birth?->format('Y-m-d'),
        'nationality' => $m->nationality,
        'occupation' => $m->occupation,
        'passport_type' => $m->passport_type,
        'passport_no' => $m->passport_no,
        'passport_expiry' => $m->passport_expiry?->format('Y-m-d'),
        'visa_no' => $m->visa_no,
    ])->values()->toJson() : '[]' }})">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold text-slate-800">Thông tin công văn</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-slate-600">Số công văn</label>
                <input type="text" name="document_no" value="{{ old('document_no', $delegation->document_no) }}"
                       placeholder="VD: 123/ĐHNL-HTQT"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Đơn vị tiếp đón</label>
                <input type="text" name="host_unit" value="{{ old('host_unit', $delegation->host_unit) }}"
                       placeholder="VD: Trung tâm Nghiên cứu Địa tin học"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-600">Nội dung làm việc</label>
                <textarea name="purpose" rows="2"
                          class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('purpose', $delegation->purpose) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Thời gian bắt đầu làm việc</label>
                <input type="datetime-local" name="work_date_from" value="{{ old('work_date_from', $delegation->work_date_from?->format('Y-m-d\TH:i')) }}"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Thời gian kết thúc làm việc</label>
                <input type="datetime-local" name="work_date_to" value="{{ old('work_date_to', $delegation->work_date_to?->format('Y-m-d\TH:i')) }}"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Địa điểm công tác</label>
                <input type="text" name="work_location" value="{{ old('work_location', $delegation->work_location) }}"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Nơi gửi công văn</label>
                <input type="text" name="recipient_units" value="{{ old('recipient_units', $delegation->recipient_units) }}"
                       placeholder="VD: PA03, PA01, Sở Ngoại vụ"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Tên nơi tạm trú</label>
                <input type="text" name="accommodation_name" value="{{ old('accommodation_name', $delegation->accommodation_name) }}"
                       placeholder="VD: THE KING HOTEL"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Địa chỉ tạm trú dự kiến</label>
                <input type="text" name="accommodation_address" value="{{ old('accommodation_address', $delegation->accommodation_address) }}"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Trạng thái</label>
                <select name="status" class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['draft' => 'Dự thảo', 'sent' => 'Đã gửi công văn', 'completed' => 'Hoàn tất'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $delegation->status) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-600">Ghi chú</label>
                <textarea name="notes" rows="2"
                          class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $delegation->notes) }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">Danh sách thành viên đoàn</h2>
            <button type="button" @click="addMember()"
                    class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm thành viên
            </button>
        </div>

        <template x-for="(member, index) in members" :key="index">
            <div class="border border-slate-200 rounded-lg p-4 relative">
                <button type="button" @click="removeMember(index)"
                        class="absolute top-3 right-3 text-slate-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <input type="hidden" :name="`members[${index}][id]`" x-model="member.id">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-slate-500">Họ và tên</label>
                        <input type="text" :name="`members[${index}][full_name]`" x-model="member.full_name"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Giới tính</label>
                        <select :name="`members[${index}][gender]`" x-model="member.gender"
                                class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">--</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Ngày sinh</label>
                        <input type="date" :name="`members[${index}][date_of_birth]`" x-model="member.date_of_birth"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Quốc tịch</label>
                        <input type="text" :name="`members[${index}][nationality]`" x-model="member.nationality"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-medium text-slate-500">Nghề nghiệp</label>
                        <input type="text" :name="`members[${index}][occupation]`" x-model="member.occupation"
                               placeholder="VD: Giảng viên, Giáo sư"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Loại hộ chiếu</label>
                        <input type="text" :name="`members[${index}][passport_type]`" x-model="member.passport_type"
                               placeholder="P" class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Số hộ chiếu</label>
                        <input type="text" :name="`members[${index}][passport_no]`" x-model="member.passport_no"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Hạn hộ chiếu</label>
                        <input type="date" :name="`members[${index}][passport_expiry]`" x-model="member.passport_expiry"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Số thị thực</label>
                        <input type="text" :name="`members[${index}][visa_no]`" x-model="member.visa_no"
                               class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
        </template>

        <p x-show="members.length === 0" class="text-sm text-slate-400 text-center py-6">Chưa có thành viên nào, bấm "Thêm thành viên" để bắt đầu.</p>
    </div>

    <div class="flex items-center justify-end gap-3 mt-6">
        <a href="{{ route('admin.delegations.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100">Hủy</a>
        <button type="submit" class="px-5 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
            {{ $isEdit ? 'Lưu thay đổi' : 'Tạo đoàn khách' }}
        </button>
    </div>
</div>

<script>
    function delegationForm(initialMembers) {
        return {
            members: initialMembers,
            addMember() {
                this.members.push({
                    id: null, full_name: '', gender: '', date_of_birth: '',
                    nationality: 'Đài Loan', occupation: '', passport_type: '',
                    passport_no: '', passport_expiry: '', visa_no: ''
                });
            },
            removeMember(index) {
                this.members.splice(index, 1);
            }
        }
    }
</script>
