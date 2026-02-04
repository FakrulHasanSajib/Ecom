@forelse($paymentlist as $key => $value)
<tr>
    <td><input type="checkbox" class="checkbox" value="{{$value->id}}"></td>
    <td>{{$loop->iteration + ($paymentlist->currentPage() - 1) * $paymentlist->perPage()}}</td>
    <td>
        <div class="button-list custom-btn-list">   
            <a href="" title="Invoice"><i class="fe-eye"></i></a>
            <a href="{{ route('wholeselleredit.payment', $value->id) }}" title="Edit">
    <i class="fe-edit"></i>
</a>

            <form method="post" action="{{route('wholeseldelete.payment')}}" class="d-inline">        
                @csrf
                <input type="hidden" value="{{$value->id}}" name="id">
                <button type="submit" title="Delete" class="delete-confirm"><i class="fe-trash-2"></i></button>
            </form>
        </div>
    </td>
    <td>{{$value->pay_code}}</td>
    <td>{{$value->wholesaler ? $value->wholesaler->business_name : 'N/A'}}</td>
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