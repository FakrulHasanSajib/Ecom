@extends('backEnd.layouts.master')
@section('title', 'Recruitment Campaigns')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .badge-soft-info { background-color: rgba(53, 163, 242, 0.1); color: #35a3f2; }
        .badge-soft-success { background-color: rgba(32, 201, 151, 0.1); color: #20c997; }
        .action-btns i { font-size: 14px; }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Recruitment Campaigns (Admin View)</h4>
            </div>
        </div>
    </div>

    <div class="card mt-3 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <h5 class="mb-0 fw-bold">Campaign List</h5>
            <a href="{{ route('admin.recruitment.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus-circle me-1"></i> Create New
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-centered table-bordered table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Assigned Dealer</th>
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
                                @if($row->referral_code)
                                    @php $dealer = \App\Models\Dealer::find($row->referral_code); @endphp
                                    <span class="badge badge-soft-info">{{ $dealer->name ?? 'Unknown' }}</span>
                                @else
                                    <span class="badge badge-soft-success">Global (Admin)</span>
                                @endif
                            </td>
                            <td>
                                <div class="input-group" style="max-width: 350px;">
                                    <input type="text" class="form-control form-control-sm" value="{{ route('frontend.recruitment.page', $row->slug) }}" id="link_{{$row->id}}" readonly>
                                    <button class="btn btn-dark btn-sm" onclick="copyLink('link_{{$row->id}}')">
                                        <i class="fa-solid fa-copy me-1"></i> Copy
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btns">
                                    {{-- View Icon (চোখ) --}}
                                    <a href="{{ route('frontend.recruitment.page', $row->slug) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Campaign">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    {{-- Edit Icon (কলম) --}}
                                    <a href="{{ route('admin.recruitment.edit', $row->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    {{-- Delete Icon (ট্র্যাশ) --}}
                                    <form action="{{ route('admin.recruitment.destroy') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="hidden_id" value="{{$row->id}}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')" title="Delete">
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

<script>
function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    
    try {
        navigator.clipboard.writeText(copyText.value);
        alert("Link Copied!");
    } catch (err) {
        document.execCommand("copy");
        alert("Link Copied!");
    }
}
</script>
@endsection