@foreach ($visibleTotalsRows as $row)
    <tr>
        <td>{{ $row['label'] }}</td>
        <td>
            @if (!empty($row['negative']))
                -{{ $formatCurrency((int) $row['amount']) }}
            @else
                {{ $formatCurrency((int) $row['amount']) }}
            @endif
        </td>
    </tr>
@endforeach
<tr class="grand-row">
    <td>{{ __('ui.invoice.totals.grand_total') }}</td>
    <td>{{ $formatCurrency($grandTotal) }}</td>
</tr>
