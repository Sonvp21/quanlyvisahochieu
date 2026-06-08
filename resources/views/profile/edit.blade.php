<x-app-layout>
<div class="p-6 max-w-2xl mx-auto space-y-5">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Hồ sơ cá nhân</h1>
        <p class="text-sm text-slate-500 mt-0.5">Cập nhật thông tin tài khoản của bạn</p>
    </div>

    {{-- UPDATE PROFILE --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
            <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-slate-800">Thông tin tài khoản</h2>
        </div>
        <div class="p-5">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- UPDATE PASSWORD --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
            <div class="w-7 h-7 bg-amber-500 rounded-lg flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-slate-800">Đổi mật khẩu</h2>
        </div>
        <div class="p-5">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- DELETE ACCOUNT --}}
    <div class="bg-white rounded-2xl border border-red-200 overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-red-100">
            <div class="w-7 h-7 bg-red-500 rounded-lg flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-red-700">Xóa tài khoản</h2>
        </div>
        <div class="p-5">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
</x-app-layout>