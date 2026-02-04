@php 
    $subtotal = Cart::instance('pos_shopping')->subtotal(); 
    $subtotal = str_replace(',','',$subtotal); 
    $subtotal = str_replace('.00', '',$subtotal); 

    $shipping = Session::get('pos_shipping') ?? 0; 
    $pos_discount = Session::get('pos_discount') ?? 0;

    // সরাসরি কার্ট থেকে আইটেম ধরে ডিসকাউন্ট হিসাব করা
    $total_product_discount = 0;
    foreach(Cart::instance('pos_shopping')->content() as $item){
        $total_product_discount += (($item->options->product_discount ?? 0) * $item->qty);
    }
    
    $total_discount = $pos_discount + $total_product_discount;
@endphp

<tr>
  <td>Sub Total</td>
  <td>{{ $subtotal }}</td>
</tr>
<tr>
  <td>Discount</td>
  <td>{{ $total_discount }}</td>
</tr>
<tr>
  <td>Total</td>
  <td>{{ ($subtotal + $shipping) - $total_discount }}</td>
</tr>