@foreach($products as $product)

 <div class="col-lg-2 col-md-3 col-sm-6 col-6" style="padding: 10px;">
        <div class="product_item wist_item">
            <div class="product_item_inner">
                <!-- Free Shipping Badge -->
                @if($product->free_shipping)
                <div class="free-shipping-badge">
                    <div class="free-shipping-icon">
                    <span class="free-shipping-text">ফ্রি ডেলিভারি</span>
                    </div>
                </div>
                @endif
                
                <!-- Product Content -->
                <div class="pro_img">
                    <a href="{{ route('product', $product->slug) }}">
                        <img src="{{ asset($product->image ? $product->image->image : '') }}" alt="{{ $product->name }}" class="img-fluid" />
                    </a>
                </div>
                <div class="pro_des">
                    <div class="pro_name">
                        <a href="{{ route('product', $product->slug) }}">{{ Str::limit($product->name, 25) }}</a>
                    </div>
                    <div class="pro_price">
                        <p><del class="old-price">৳{{ $product->old_price }}</del></p>
                        <p><span class="new-price">৳{{ $product->new_price }}</span></p>
                    </div>
                </div>
                <div class="pro_btn">
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}" />
                        <input type="hidden" name="qty" value="1" />
                        <button type="submit" class="btn btn-primary" style="background: {{ $generalsetting->headercolor }};">অর্ডার করুন +</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach