@extends('backEnd.layouts.master')
@section('title', 'Landing Page Manage')

@section('css')
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />

    <style>
        .nav-tabs-custom-wrapper {
            background: #fff;
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            display: inline-block;
            margin-top: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .nav-pills-custom .nav-link {
            color: #666;
            background: transparent;
            margin: 0 8px;
            border-radius: 30px;
            padding: 12px 35px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .nav-pills-custom .nav-link:hover {
            background: #f1f3f5;
            color: #333;
            transform: translateY(-2px);
        }

        .nav-pills-custom .nav-link.active {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            transform: translateY(-2px);
        }

        /* Modern Table Design */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-modern thead th {
            background-color: #f8f9fa;
            border-bottom: none;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 15px 20px;
        }

        .table-modern tbody tr {
            transition: all 0.2s;
            border-bottom: 1px solid #f0f2f5;
        }

        .table-modern tbody tr:last-child {
            border-bottom: none;
        }

        .table-modern tbody tr:hover {
            background-color: #f8faff;
            transform: scale(1.002);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .table-modern td {
            padding: 15px 20px;
            vertical-align: middle;
            color: #495057;
            font-size: 0.95rem;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s;
            border: none;
            margin: 0 3px;
        }

        .action-btn i {
            font-size: 1.1rem;
        }

        .btn-soft-primary {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .btn-soft-primary:hover {
            background: #0d6efd;
            color: white;
        }

        /* New Edit Button Color - Orange */
        .btn-soft-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .btn-soft-warning:hover {
            background: #ffc107;
            color: white;
        }

        .btn-soft-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-soft-danger:hover {
            background: #dc3545;
            color: white;
        }

        .btn-soft-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .btn-soft-success:hover {
            background: #198754;
            color: white;
        }

        .badge-soft-success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-soft-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid" style="background-color: #f4f7f6; min-height: 100vh; padding-bottom: 50px;">

        <!-- Top Navigation Tabs -->
        <div class="row mb-4 justify-content-center">
            <div class="col-auto">
                <div class="nav-tabs-custom-wrapper">
                    <div class="nav nav-pills nav-pills-custom">
                        <a class="nav-link active" href="#">Campaign</a>
                        <a class="nav-link" href="{{ route('landing_page.manage') }}">Landing Page Theme</a>
                        <a class="nav-link" href="{{ route('campaign.create') }}">Landing Page Create</a>
                        <a class="nav-link" href="#">Build Landing Page</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric or Title Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title text-dark" style="font-weight: 800; font-size: 1.8rem; letter-spacing: -0.5px;">
                        Manage Campaigns
                    </h4>
                    <!-- Optional: Add a subtle 'Total campaigns' badge or summary here if needed -->
                    <span class="text-muted" style="font-size: 0.95rem;">Total campaigns: {{ count($show_data) }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <table id="datatable-buttons" class="table table-modern dt-responsive nowrap w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-3 rounded-start">SL</th>
                                    <th class="py-3">Landing Page Title</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 pe-3 rounded-end">Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($show_data as $key => $value)
                                    <tr>
                                        <td class="ps-3"><span
                                                style="font-weight: 600; color: #6c757d;">#{{$loop->iteration}}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-soft-primary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 35px; height: 35px;">
                                                    <i class="fe-file-text text-primary" style="font-size: 16px;"></i>
                                                </div>
                                                <div style="font-weight: 600; font-size: 0.95rem; color: #343a40;">
                                                    {{ Str::limit($value->name, 60) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($value->status == 1)
                                                <span class="badge badge-soft-success"
                                                    style="font-weight: 600; padding: 6px 12px;">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger"
                                                    style="font-weight: 600; padding: 6px 12px;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="pe-3">
                                            <div class="button-list">
                                                <a href="{{url('campaign', $value->slug)}}" class="action-btn btn-soft-primary"
                                                    target="_blank" data-bs-toggle="tooltip" title="View Page">
                                                    <i class="fe-eye"></i>
                                                </a>

                                                @if($value->status == 1)
                                                    <form method="post" action="{{route('campaign.inactive')}}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                        <button type="button" class="action-btn btn-soft-warning change-confirm"
                                                            data-bs-toggle="tooltip" title="Deactivate">
                                                            <i class="fe-thumbs-down"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="post" action="{{route('campaign.active')}}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                        <button type="button" class="action-btn btn-soft-success change-confirm"
                                                            data-bs-toggle="tooltip" title="Activate">
                                                            <i class="fe-thumbs-up"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{route('campaign.edit', $value->id)}}"
                                                    class="action-btn btn-soft-info" data-bs-toggle="tooltip"
                                                    title="Edit Campaign">
                                                    <i class="fe-edit-1"></i>
                                                </a>

                                                <form method="post" action="{{route('campaign.destroy')}}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                    <button type="submit" class="action-btn btn-soft-danger delete-confirm"
                                                        data-bs-toggle="tooltip" title="Delete Permanent">
                                                        <i class="fe-trash-2"></i> <!-- Changed icon to trash for clarity -->
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
@endsection


@section('script')
    <!-- third party js -->
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script
        src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script
        src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script
        src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{asset('/public/backEnd/')}}/assets/js/pages/datatables.init.js"></script>
    <!-- third party js ends -->
@endsection