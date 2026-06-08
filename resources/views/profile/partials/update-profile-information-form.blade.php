<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Họ và tên')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-xs text-slate-500">
                    Email chưa được xác thực.
                    <button form="send-verification"
                        class="text-blue-500 hover:underline text-xs font-medium">
                        Gửi lại email xác thực
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                <p class="mt-1 text-xs text-emerald-600">Email xác thực đã được gửi lại!</p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>Lưu thông tin</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-xs text-emerald-600 font-medium">
                Đã lưu!
            </p>
            @endif
        </div>
    </form>
</section>