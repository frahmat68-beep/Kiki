<x-guest-layout
    :page-title="__('app.auth.login_page_title')"
    :eyebrow="null"
    :heading="__('app.auth.login_title')"
    :subheading="__('app.auth.login_note')"
    :aside-eyebrow="null"
    :aside-heading="__('app.auth.login_benefit_1')"
    :aside-text="__('app.auth.login_benefit_2')"
    :aside-points="[]"
>
    <div class="space-y-4">
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="login-email" class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                    {{ __('app.auth.email') }}
                </label>
                <input
                    id="login-email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="input w-full rounded-2xl px-4 py-3 text-sm"
                    placeholder="{{ __('app.auth.email_placeholder') }}"
                >
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between gap-3">
                    <label for="login-password" class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                        {{ __('app.auth.password') }}
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700" data-skip-loader="true">
                        {{ __('app.auth.forgot_password') }}
                    </a>
                </div>
                <x-password-input
                    id="login-password"
                    name="password"
                    :required="true"
                    placeholder="{{ __('app.auth.password_placeholder_mask') }}"
                    autocomplete="current-password"
                    input-class="input w-full rounded-2xl px-4 py-3 text-sm"
                />
            </div>

            <button class="btn-primary inline-flex w-full items-center justify-center rounded-2xl px-4 py-3 text-sm font-semibold">
                {{ __('app.auth.login_button') }}
            </button>
        </form>

        <div class="relative flex items-center py-2">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="mx-4 flex-shrink text-[10px] font-semibold uppercase tracking-widest text-slate-400">Atau masuk dengan</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <a href="{{ route('social.redirect', 'google') }}" class="inline-flex w-full items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-all hover:bg-slate-50 hover:shadow-sm">
            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Google
        </a>

        <div class="border-t border-slate-200/80 pt-4 text-sm text-slate-500">
            {{ __('app.auth.no_account') }}
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700" data-skip-loader="true">
                {{ __('app.auth.register_now') }}
            </a>
        </div>
    </div>
</x-guest-layout>
