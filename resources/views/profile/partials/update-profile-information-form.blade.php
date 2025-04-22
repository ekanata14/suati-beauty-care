<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('user.update.profile') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        <div>
            <x-input-label for="foto_profil" :value="__('Profile Photo')" />
            @if ($user->Konsumen->foto_profil)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $user->Konsumen->foto_profil) }}" alt="{{ __('Profile Photo') }}"
                        class="w-40 h-40 rounded-full object-cover">
                </div>
            @else
                <p class="text-secondary">No Profile Photo</p>
            @endif
            <input id="foto_profil" name="foto_profil" type="file" class="mt-1 block w-full"
                accept="image/jpeg,image/png,image/jpg" />
            <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />
        </div>
        <div class="grid grid-cols-2 gap-6">

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)"
                    required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                    required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="tanggal_lahir" :value="__('Date of Birth')" />
                <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full"
                    :value="old('tanggal_lahir', $user->Konsumen->tanggal_lahir)" required />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
            </div>

            <div>
                <x-input-label for="jenis_kelamin" :value="__('Gender')" />
                <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full">
                    <option value="Laki-Laki"
                        {{ old('jenis_kelamin', $user->Konsumen->jenis_kelamin) === 'Laki-Laki' ? 'selected' : '' }}>
                        {{ __('Laki-Laki') }}</option>
                    <option value="Perempuan"
                        {{ old('jenis_kelamin', $user->Konsumen->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>
                        {{ __('Perempuan') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
            </div>

            <div>
                <x-input-label for="telepon" :value="__('Phone')" />
                <x-text-input id="telepon" name="telepon" type="text" class="mt-1 block w-full" :value="old('telepon', $user->Konsumen->telepon)"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('telepon')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
