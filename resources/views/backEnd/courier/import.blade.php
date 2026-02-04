@extends('backEnd.layouts.master')
@section('title','Courier Import')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Steadfast/Courier Payment Excel Upload</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.courier.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Select Courier</label>
                        <select name="courier_name" class="form-control">
                            <option value="Steadfast">Steadfast</option>
                            <option value="Pathao">Pathao</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Upload Excel/CSV File</label>
                        <input type="file" name="file" class="form-control" required accept=".csv, .xlsx">
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Process File</button>
            </form>
            <hr>
        <h4 class="mt-4">Upload History & Logs</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>File Name</th>
                        <th>Summary (Taka)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ledgers as $data)
                    <tr>
                        <td>
                            <strong>{{ $data->created_at->format('d M, Y') }}</strong><br>
                            <small class="text-muted">{{ $data->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <span class="text-primary"><i class="fas fa-file-excel"></i> {{ $data->sheet_name }}</span><br>
                            <small style="color: #666;">{{ ucfirst($data->courier_name) }}</small>
                        </td>
                        <td>
                            <div style="font-size: 16px; font-weight: bold; color: #28a745;">
                                + {{ number_format($data->total_credit, 2) }} Tk
                            </div>
                        </td>
                        <td>
                            @if($data->delivered_orders > 0)
                                <span class="badge" style="background-color: #28a745; color: #fff; padding: 6px 10px; border-radius: 4px; display: inline-block; margin-bottom: 2px;">
                                    Delivered: {{ $data->delivered_orders }}
                                </span>
                            @endif
                            
                            @if($data->returned_orders > 0)
                                <span class="badge" style="background-color: #dc3545; color: #fff; padding: 6px 10px; border-radius: 4px; display: inline-block; margin-bottom: 2px;">
                                    Returned: {{ $data->returned_orders }}
                                </span>
                            @endif

                            @if($data->delivered_orders == 0 && $data->returned_orders == 0)
                                <span class="badge" style="background-color: #6c757d; color: #fff; padding: 6px 10px; border-radius: 4px; display: inline-block;">
                                    No Update / All Failed
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $ledgers->links() }} 
            </div>
        </div>
        </div>
    </div>
</div>
@endsection