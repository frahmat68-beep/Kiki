@extends('layouts.admin', ['activePage' => 'users'])

@section('title', 'User Detail')
@section('page_title', 'User Detail')

@section('content')
    @php
        $profile = $user->profile;
        $addressText = $profile?->address_text ?? '-';
        $formatStatus = fn (bool $ok, string $okText = 'Verified', string $noText = 'Unverified') => $ok
            ? '<span class="status-chip status-chip-success">' . $okText . '</span>'
            : '<span class="status-chip status-chip-warning">' . $noText . '</span>';
        $formatProfileStatus = fn (bool $ok) => $ok
            ? '<span class="status-chip status-chip-info">Completed</span>'
            : '<span class="status-chip status-chip-muted">Incomplete</span>';
    @endphp

    <div class="mx-auto max-w-6xl space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">User</p>
                <h2 class="text-2xl font-semibold text-blue-700">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600">← Kembali ke Users</a>
        </div>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1.2fr,0.8fr]">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-700">Profil User</h3>
                <div class="mt-4 grid grid-cols-1 gap-3 text-sm text-slate-600 sm:grid-cols-2">
                    <p><span class="font-semibold text-slate-800">Nama Lengkap:</span> {{ $profile?->full_name ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">NIK:</span> {{ $profile?->nik ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Tanggal Lahir:</span> {{ optional($profile?->date_of_birth)->format('d M Y') ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Gender:</span> {{ $profile?->gender ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">No. Telepon:</span> {{ $profile?->phone ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Role:</span> {{ strtoupper($user->role ?? 'user') }}</p>
                    <p class="sm:col-span-2"><span class="font-semibold text-slate-800">Alamat:</span> {{ $addressText }}</p>
                    <p><span class="font-semibold text-slate-800">Google Maps:</span> {{ $profile?->maps_url ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Kode Pos:</span> {{ $profile?->postal_code ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Kontak Darurat:</span> {{ $profile?->emergency_name ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Hubungan Darurat:</span> {{ $profile?->emergency_relation ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">No. Darurat:</span> {{ $profile?->emergency_phone ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-800">Email Status:</span> {!! $formatStatus((bool) $user->email_verified_at) !!}</p>
                    <p><span class="font-semibold text-slate-800">Phone Status:</span> {!! $formatStatus((bool) ($profile?->phone_verified_at), 'Verified', 'Unverified') !!}</p>
                    <p><span class="font-semibold text-slate-800">Profile Status:</span> {!! $formatProfileStatus($user->profileIsComplete()) !!}</p>
                    <p><span class="font-semibold text-slate-800">Completed At:</span> {{ optional($profile?->completed_at)->format('d M Y H:i') ?? '-' }}</p>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-slate-900">Order Terbaru</h4>
                    <div class="mt-3 space-y-2">
                        @forelse ($user->orders as $order)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 p-3 text-sm">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $order->order_number ?? ('ORD-' . $order->id) }}</p>
                                    <p class="text-xs text-slate-500">{{ strtoupper($order->status_pembayaran ?? 'pending') }} • {{ strtoupper($order->status_pesanan ?? '-') }}</p>
                                </div>
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat</a>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada order.</p>
                        @endforelse
                    </div>
                </div>
            </article>

            <aside class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Aksi Keamanan</h3>
                <p class="text-sm text-slate-500">
                    Password user tidak ditampilkan ke admin. Sistem hanya menyimpan hash password.
                </p>

                <form method="POST" action="{{ route('admin.users.set-password', $user) }}" class="space-y-3 rounded-xl border border-slate-200 p-4">
                    @csrf
                    <p class="text-sm font-semibold text-slate-900">Set Password Baru</p>
                    <div>
                        <label class="text-xs font-semibold text-slate-500">Password Baru</label>
                        <input
                            type="password"
                            name="new_password"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                            required
                        >
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            name="new_password_confirmation"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                            required
                        >
                    </div>
                    <button class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Simpan Password Baru
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                    @csrf
                    <button class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-600">
                        Kirim Reset Password ke Email
                    </button>
                </form>
            </aside>
        </section>
    </div>
@endsection
