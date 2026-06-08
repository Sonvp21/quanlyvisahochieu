<section>
    <p class="text-sm text-slate-500 mb-4">
        Sau khi tài khoản bị xóa, tất cả dữ liệu sẽ bị xóa vĩnh viễn. Vui lòng sao lưu trước khi thực hiện.
    </p>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        Xóa tài khoản
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-800 mb-2">Xóa tài khoản</h2>
            <p class="text-sm text-slate-500 mb-4">
                Bạn có chắc chắn muốn xóa tài khoản? Thao tác này không thể hoàn tác.
                Nhập mật khẩu để xác nhận.
            </p>

            <div class="mb-4">
                <x-input-label for="password" value="Mật khẩu" class="sr-only" />
                <x-text-input id="password" name="password" type="password"
                    class="block w-full" placeholder="••••••••" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Hủy</x-secondary-button>
                <x-danger-button>Xóa tài khoản</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>