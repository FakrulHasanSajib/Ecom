@extends('backEnd.layouts.master')
@section('title', 'Supplier List')
@section('css')
    <link href="{{asset('backEnd')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#addSupplierModal"><i class="fe-plus"></i> Add New</button>
                    </div>
                    <h4 class="page-title">Supplier List</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="datatable">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->phone }}</td>
                                            <td>{{ $value->address }}</td>
                                            <td>
                                                @if ($value->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="button-list">
                                                    <a href="{{ route('admin.supplier.history', $value->id) }}"
                                                        class="btn btn-xs btn-info" title="History"><i
                                                            class="fa fa-history"></i></a>
                                                    <button type="button" class="btn btn-xs btn-primary edit_supplier"
                                                        data-id="{{ $value->id }}" title="Edit"><i
                                                            class="fa fa-pencil"></i></button>
                                                    <form action="{{ route('admin.supplier.destroy', $value->id) }}"
                                                        method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-danger delete-confirm"
                                                            title="Delete"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.supplier.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Phone *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.supplier.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Phone *</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Address</label>
                            <textarea name="address" id="edit_address" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('backEnd')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('backEnd')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            "use strict";
            $("#datatable").DataTable();

            $('.edit_supplier').on('click', function () {
                var id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.supplier.edit', '') }}/" + id,
                    dataType: "json",
                    success: function (data) {
                        $('#edit_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_phone').val(data.phone);
                        $('#edit_address').val(data.address);
                        $('#edit_status').val(data.status);
                        $('#editSupplierModal').modal('show');
                    }
                });
            });
        });
    </script>
@endsection