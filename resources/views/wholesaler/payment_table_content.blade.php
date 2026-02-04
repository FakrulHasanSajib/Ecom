 @forelse($paymentlist as $key => $value)
<tr>
    <td><input type="checkbox" class="checkbox" value="{{$value->id}}"></td>
    <td>{{$loop->iteration + ($paymentlist->currentPage() - 1) * $paymentlist->perPage()}}</td>
    
    <td>{{$value->pay_code}}</td>
    <td><strong>{{$value->payment_method}}</strong></td>
    <td>৳{{number_format($value->pay_amount, 2)}}</td>
    <td>{{date('d-m-Y', strtotime($value->date))}}</td>
    <td>{{$value->paynode}}</td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center">
        <div class="alert alert-info">
            <i class="fe-info-circle"></i> No payments found matching your criteria.
        </div>
    </td>
</tr>
@endforelse