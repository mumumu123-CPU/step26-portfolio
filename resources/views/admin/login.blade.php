  <div class="w-full fixed top-0 left-0 z-50 px-8 py-4 bg-white shadow-md flex justify-between items-center">
      <a href="{{ route('hospital.index') }}" class="text-base text-gray-800 font-bold hover:underline">
          精神科評価サイト
      </a>
      <a href="{{ route('admin.login.form') }}" class="text-base text-gray-800 font-bold hover:underline">
          管理者ログイン
      </a>
  </div>
  <x-guest-layout>


      <!-- Session Status -->
      <x-auth-session-status class="mb-4" :status="session('status')" />



      <form method="POST" action="{{ route('admin.login.post') }}">
          @csrf

          <!-- Email Address -->
          <div>
              <x-input-label for="email" :value="__('Email')" />
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                  required autofocus autocomplete="username" />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          <!-- Password -->
          <div class="mt-4">
              <x-input-label for="password" :value="__('Password')" />

              <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                  autocomplete="current-password" />

              <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <!-- Remember Me -->
          <div class="block mt-4">
              <label for="remember_me" class="inline-flex items-center">
                  <input id="remember_me" type="checkbox"
                      class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                  <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
              </label>
          </div>

          <div class="flex items-center justify-end mt-4">
              @if (Route::has('password.request'))
                  <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      href="{{ route('password.request') }}">
                      {{ __('Forgot your password?') }}
                  </a>
              @endif

              <x-primary-button class="ms-3">
                  {{ __('Log in') }}
              </x-primary-button>
          </div>
      </form>
  </x-guest-layout>

  <footer class="bg-white text-center py-6">
      <a href="{{ route('hospital.index') }}" class="text-base tetext-gray-800 font-bold hover:underline">
          精神科評価サイト
      </a>
  </footer>

  <!--背景色は以下に設定resources/views/layouts/guest.blade.php-->
