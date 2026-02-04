@if(count($product_ids) > 0)
<table class="table table-bordered aiz-table">
  <thead>
  	<tr>
  		<td width="50%">
          <span>Product</span>
  		</td>
      <td data-breakpoints="lg" width="20%">
          <span> Price</span>
  		</td>
  		<td data-breakpoints="lg" width="20%">
          <span> Loyalty</span>
  		</td>

  	</tr>
  </thead>
  <tbody>
      @foreach ($product_ids as $key => $id)
      	@php
      		$product = \App\Models\Product::findOrFail($id);
      	@endphp
          <tr>
            <td>
              <div class="from-group row">
                <div class="col-auto">
                  <img class="size-60px img-fit" src="">
                </div>
                <div class="col">
                  <span>{{  $product->name }}</span>
                </div>
              </div>
            </td>
            <td>
                <span>{{ $product->new_price }}</span>
            </td>
            <td>
                <input type="number" lang="en" name="loyality_{{ $id }}" value="20" min="0" step="1" class="form-control" required>
            </td>

          </tr>
      @endforeach
  </tbody>
</table>
@endif