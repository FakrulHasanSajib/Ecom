@extends('backEnd.dealer.panel.layout.master')
@section('title', 'Recruitment Campaigns')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .card-header { background: #fff; border-bottom: 1px solid #eee; }
        .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .action-btns i { font-size: 14px; }
        .badge-soft-success { background-color: rgba(32, 201, 151, 0.1); color: #20c997; }
        .badge-soft-danger { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-dark">Campaign List</h5>
                    <a href="{{ route('dealer.recruitment.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-plus-circle me-1"></i> Create New
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 border">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Campaign Link (Copy This)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-medium">{{ $row->title }}</td>
                                    <td>
                                        @if($row->status == 1)
                                            <span class="badge badge-soft-success">Active</span>
                                        @else
                                            <span class="badge badge-soft-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group" style="max-width: 350px;">
                                            <input type="text" class="form-control form-control-sm border-end-0" 
                                                   value="{{ route('frontend.recruitment.page', $row->slug) }}" 
                                                   id="link_{{$row->id}}" readonly>
                                            <button class="btn btn-dark btn-sm" onclick="copyLink('link_{{$row->id}}')">
                                                <i class="fa-solid fa-copy me-1"></i> Copy
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btns">
                                            {{-- View Icon (চোখ) --}}
                                            <a href="{{ route('frontend.recruitment.page', $row->slug) }}" 
                                               target="_blank" class="btn btn-sm btn-outline-info" title="View Campaign">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            {{-- Edit Icon (কলম) --}}
                                            <a href="{{ route('dealer.recruitment.edit', $row->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            
                                            {{-- Delete Icon (ট্র্যাশ) --}}
                                            <form action="{{ route('dealer.recruitment.destroy') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="hidden_id" value="{{$row->id}}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this campaign?')" title="Delete">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
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

<script>
function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    
    try {
        navigator.clipboard.writeText(copyText.value);
        alert("Campaign Link Copied!");
    } catch (err) {
        document.execCommand("copy");
        alert("Link Copied!");
    }
}
</script>
@endsection