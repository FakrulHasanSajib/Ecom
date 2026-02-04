@extends('backEnd.layouts.master') 

@section('title', 'Quick Tabs Manage')
@section('content')
<div class="row mt-4"> {{-- ⬅️ এখানে mt-4 যুক্ত করা হয়েছে --}}
    <div class="col-12">
        <div class="card shadow-lg border-0 rounded-lg">
            
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center py-3">
                <h3 class="card-title mb-0 font-weight-bold"><i class="fas fa-th-list me-2"></i>  Quick Tabs Manage</h3>
                <a href="{{ route('admin.quick_tabs.create') }}" class="btn btn-light btn-sm text-info shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Add Quick Tab 
                </a>
            </div>
            
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 5%;">ID</th>
                                <th style="width: 25%;">নাম (Tab Name)</th>      
                                <th style="width: 35%;">লিঙ্ক (Tab Link)</th>      
                                <th style="width: 10%;" class="text-center">ক্রম (Order)</th>
                                <th style="width: 10%;" class="text-center">স্ট্যাটাস</th>
                                <th style="width: 15%;" class="text-center">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quickTabs as $tab)
                            <tr>
                                <td>{{ $tab->id }}</td>
                                <td>{{ $tab->tab_name }}</td>  
                                <td><code class="text-primary">{{ $tab->tab_link }}</code></td>
                                <td class="text-center">{{ $tab->order }}</td>
                                <td class="text-center">
                                    @if($tab->is_active)
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> সক্রিয়</span> 
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-times-circle"></i> নিষ্ক্রিয়</span> 
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Quick Tab Actions">
                                        {{-- সম্পাদনা বাটন --}}
                                        <a href="{{ route('admin.quick_tabs.edit', $tab->id) }}" class="btn btn-warning btn-sm" title="সম্পাদনা করুন">
                                            <i class="fas fa-edit"></i> 
                                        </a>
                                        
                                        {{-- টগল বাটন --}}
                                        <form action="{{ route($tab->is_active ? 'admin.quick_tabs.inactive' : 'admin.quick_tabs.active') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $tab->id }}">
                                            <button type="submit" class="btn btn-{{ $tab->is_active ? 'danger' : 'success' }} btn-sm" title="{{ $tab->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                                <i class="fas fa-toggle-{{ $tab->is_active ? 'off' : 'on' }}"></i>
                                            </button>
                                        </form>

                                        {{-- ডিলিট বাটন --}}
                                        <form action="{{ route('admin.quick_tabs.destroy') }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $tab->id }}">
                                            <button type="submit" class="btn btn-secondary btn-sm" title="ডিলিট করুন" onclick="return confirm('আপনি কি নিশ্চিত যে আপনি এটি ডিলিট করতে চান?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-1"></i> কোনো Quick Tab পাওয়া যায়নি। নতুন করে যোগ করুন।
                                </td> 
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection