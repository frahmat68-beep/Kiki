<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    @php
        $snapSrc = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapSrc }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body>

<h2>Payment</h2>
<p>Booking Ref: {{ $booking->reference }}</p>
<p>Total: Rp {{ number_format($amount) }}</p>

<button id="pay-button">Pay Now</button>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}');
};
</script>

</body>
</html>
