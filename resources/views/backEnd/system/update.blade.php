@extends('backEnd.layouts.master')
@section('title', 'System Update')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">System Update</li>
                        </ol>
                    </div>
                    <h4 class="page-title">System Update</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Upload Update Package</h4>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.system.update.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="version" class="form-label">Version Number</label>
                                <input type="text" name="version" class="form-control" id="version" placeholder="e.g. 2.4"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="changelog" class="form-label">Changelog / Message</label>
                                <textarea name="changelog" class="form-control" rows="3"
                                    placeholder="What's new in this update?"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="update_file" class="form-label">Update File (.zip)</label>
                                <input type="file" name="update_file" class="form-control" id="update_file" required
                                    accept=".zip">
                                <small class="text-muted">Upload the system patch zip file. This will replace files and run
                                    migrations automatically.</small>
                            </div>

                            <button type="submit" class="btn btn-primary"
                                onclick="return confirm('Are you sure you want to update the system? This action cannot be undone.')">
                                <i class="fe-upload cloud-upload"></i> Start Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Update History</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th>Changelog</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($updates as $update)
                                        <tr>
                                            <td><span class="badge bg-info">{{ $update->version }}</span></td>
                                            <td>{{ Str::limit($update->changelog, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($update->created_at)->format('d M, Y h:i A') }}</td>
                                            <td>
                                                @if($update->status == 'success')
                                                    <span class="badge bg-success">Success</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
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