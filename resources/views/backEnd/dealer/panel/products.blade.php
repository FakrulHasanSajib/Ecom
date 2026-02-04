@extends('backEnd.dealer.panel.layout.master')
@section('title', 'My Products')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">My Products</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>My Price</th>
                                        <th>Commission Setting</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td><img src="{{asset($product->image->image)}}" height="50"></td>
                                            <td>{{$product->name}}</td>
                                            <td>{{$product->pivot->dealer_price}}</td>
                                            <td>
                                                <form action="{{route('dealer.commission.update')}}" method="POST" class="row">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                                    <div class="col-sm-3">
                                                        <label class="form-label text-muted mb-0 font-12">Reseller Price</label>
                                                        <input type="number" step="0.01" name="reseller_price"
                                                            class="form-control"
                                                            value="{{$product->pivot->reseller_price > 0 ? $product->pivot->reseller_price : $product->pivot->dealer_price}}"
                                                            placeholder="Reseller Price">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="form-label text-muted mb-0 font-12">Commission</label>
                                                        <input type="number" step="0.01" name="commission_amount"
                                                            class="form-control" value="{{$product->pivot->commission_amount}}"
                                                            placeholder="Amount">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="form-label text-muted mb-0 font-12">Type</label>
                                                        <select name="commission_type" class="form-control">
                                                            <option value="fixed" {{$product->pivot->commission_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                                            <option value="percent"
                                                                {{$product->pivot->commission_type == 'percent' ? 'selected' : ''}}>Percent</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label class="d-block">&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection