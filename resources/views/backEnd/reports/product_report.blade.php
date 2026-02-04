@extends('backEnd.layouts.master')
@section('title', 'Product Order Report')
@section('content')

    <div class="container-fluid py-2">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="mb-0 fw-bold text-primary">Product Order Report</h4>
                    <form method="GET" class="row mb-2 g-1">
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control" placeholder="Search SKU "
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="filter" class="form-select">
                                <option value="date" {{ $filter == 'date' ? 'selected' : '' }}>Date-wise</option>
                                <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Week-wise</option>
                                <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Month-wise</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">🔍 Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('reports.product_report') }}"
                                class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center rounded-3 overflow-hidden">
                        <thead class="table-primary text-dark fw-semibold small">
                            <tr>
                                <th>SKU</th>
                                <th>Total Orders</th>
                                @foreach ($statuses as $label)
                                    <th>{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report_data as $row)
                                <tr>
                                    <td>{{ $row->SKU }}</td>
                                    <td class="fw-bold text-primary">{{ $row->TotalOrders }}</td>
                                    @foreach ($statuses as $label)
                                        <td>{{ $row->$label }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($statuses) + 2 }}" class="text-center text-danger fw-semibold">
                                        No data found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $report_data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

@endsection