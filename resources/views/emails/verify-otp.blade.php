<form method="POST" action="{{ route('otp.verify') }}">
@csrf

<input type="text" name="otp" maxlength="6" placeholder="Masukkan OTP"
 class="w-full rounded border px-4 py-2">

<button class="mt-4 w-full bg-blue-600 text-white py-2 rounded">
Verifikasi
</button>
</form>
<form method="POST" action="{{ route('otp.resend') }}">
@csrf
<button class="text-sm text-blue-500 mt-3">Kirim ulang OTP</button>
</form>
