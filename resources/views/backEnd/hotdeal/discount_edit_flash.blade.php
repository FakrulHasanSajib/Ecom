@if(count($product_ids) > 0)
<table class="table table-bordered aiz-table">
    <thead>
      <tr>
        <td width="50%">
            <span>Product}</span>
        </td>
        <td data-breakpoints="lg" width="20%">
            <span>Old Price</span>
        </td>
        <td data-breakpoints="lg" width="20%">
            <span>New Price</span>
        </td>

      </tr>
    </thead>
    <tbody>
        @foreach ($product_ids as $key => $id)
            @php
              $product = \App\Models\Product::findOrFail($id);
              $flash_deal_product = \App\Models\Flashdealpro::where('flash_deal_id', $flash_deal_id)->where('product_id', $product->id)->first();
            @endphp
            <tr>
                <td>
                  <div class="form-group row">
                      <div class="col-auto">
                          <img src="" class="size-60px img-fit" >
                      </div>
                      <div class="col">
                          <span>{{  $product->name  }}</span>
                      </div>
                  </div>
                </td>
                <td>
                    <span>{{ $product->old_price }}</span>
                </td>
                <td>
                    <input type="number"  name="discount_{{ $id }}" value="{{ $product->new_price }}" min="0" step="1" class="form-control" required>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
@endif
