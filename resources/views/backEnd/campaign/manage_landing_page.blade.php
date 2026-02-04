@extends('backEnd.layouts.master')
@section('title', 'Landing Page Manage')
@section('css')
    <style>
        .theme-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .theme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .theme-img-wrapper {
            height: 200px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .theme-img {
            width: 100%;
            height: auto;
            display: block;
        }

        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .theme-body {
            padding: 20px;
        }

        .theme-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #333;
        }

        .theme-meta {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .theme-meta i {
            color: #6c5ce7;
            margin-right: 5px;
        }

        .btn-create {
            background-color: #00b894;
            color: white;
            border: none;
            padding: 8px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-create:hover {
            background-color: #00a383;
            color: white;
            transform: scale(1.05);
        }

        .nav-tabs-custom-wrapper {
            background: #fff;
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            display: inline-block;
            margin-top: 60px;
            /* Increased to separate from header */
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

        .page-header-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Top Navigation Tabs -->
        <div class="row mb-5 justify-content-center">
            <div class="col-auto">
                <div class="nav-tabs-custom-wrapper">
                    <div class="nav nav-pills nav-pills-custom">
                        <a class="nav-link" href="{{ route('campaign.index') }}">Campaign</a>
                        <a class="nav-link active" href="#">Landing Page Theme</a>
                        <a class="nav-link" href="{{ route('campaign.create') }}">Landing Page Create</a>
                        <a class="nav-link" href="#">Build Landing Page</a>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="page-header-title">Landing Page Manage</h2>

        <div class="row">
            <!-- Theme 7: Book Edition (New) -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="theme-card">
                    <div class="theme-img-wrapper">
                        <!-- Placeholder or actual image -->
                        <div
                            style="width: 100%; height: 100%; background: #00204a; display: flex; align-items: center; justify-content: center; color: #d4af37; flex-direction: column;">
                            <i class="fas fa-quran fa-3x mb-2"></i>
                            <span>Royal Islamic</span>
                        </div>
                    </div>
                    <div class="theme-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="theme-title">Theme 7: Royal Book</div>
                            <div class="theme-meta">
                                <i class="far fa-star"></i> 7 Components
                            </div>
                        </div>
                        <a href="{{ route('campaign.create.seven') }}" class="btn-create">Create</a>
                    </div>
                </div>
            </div>

            <!-- Theme 6: Dark (Existing) -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="theme-card">
                    <div class="theme-img-wrapper">
                        <div
                            style="width: 100%; height: 100%; background: #1a1a1a; display: flex; align-items: center; justify-content: center; color: #00b894; flex-direction: column;">
                            <i class="fas fa-mobile-alt fa-3x mb-2"></i>
                            <span>Dark Gadget</span>
                        </div>
                    </div>
                    <div class="theme-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="theme-title">Theme 6: Dark Tech</div>
                            <div class="theme-meta">
                                <i class="far fa-star"></i> 6 Components
                            </div>
                        </div>
                        <!-- Assuming create six exists or generic create with query param -->
                        <a href="{{ route('campaign.create') }}?theme=6" class="btn-create">Create</a>
                    </div>
                </div>
            </div>

            <!-- Generic Theme 1 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="theme-card">
                    <div class="theme-img-wrapper">
                        <div
                            style="width: 100%; height: 100%; background: #fff; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: center; color: #333; flex-direction: column;">
                            <i class="fas fa-shopping-bag fa-3x mb-2"></i>
                            <span>Standard Shop</span>
                        </div>
                    </div>
                    <div class="theme-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="theme-title">Theme 1: Standard</div>
                            <div class="theme-meta">
                                <i class="far fa-star"></i> 4 Components
                            </div>
                        </div>
                        <a href="{{ route('campaign.create') }}?theme=1" class="btn-create">Create</a>
                    </div>
                </div>
            </div>

            <!-- Generic Theme 2 -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="theme-card">
                    <div class="theme-img-wrapper">
                        <div
                            style="width: 100%; height: 100%; background: #ff7675; display: flex; align-items: center; justify-content: center; color: white; flex-direction: column;">
                            <i class="fas fa-fire fa-3x mb-2"></i>
                            <span>Hot Deals</span>
                        </div>
                    </div>
                    <div class="theme-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="theme-title">Theme 2: Deals</div>
                            <div class="theme-meta">
                                <i class="far fa-star"></i> 3 Components
                            </div>
                        </div>
                        <a href="{{ route('campaign.create') }}?theme=2" class="btn-create">Create</a>
                    </div>
                </div>
            </div>




            <div class="col-xl-3 col-md-6 mb-4">
    <div class="card h-100 border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-center rounded-top" 
                 style="height: 180px; background: linear-gradient(135deg, #009961 0%, #1F2937 100%);">
                <div class="text-center">
                    <i class="fas fa-video fa-4x text-white mb-2"></i>
                    <h5 class="text-white fw-bold">Video & Story</h5>
                </div>
            </div>
            
            <div class="p-3">
                <h5 class="card-title fw-bold mb-1">Theme 9: Video Story</h5>
                <p class="text-muted small mb-3">
                    <i class="fas fa-star text-warning"></i> Video, 4 Images & Clean Form
                </p>
                
                <div class="d-grid">
                    <a href="{{ route('campaign.create', ['theme' => 9]) }}" class="btn btn-success">CREATE</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-sm-6 col-xl-3">
    <div class="card">
        <div class="card-body p-0">
            <div class="p-4 text-center text-white" 
                 style="background: linear-gradient(135deg, #0f3d2e 0%, #d4af37 100%); border-radius: 3px 3px 0 0;">
                <div class="font-size-24 mb-3">
                    <i class="fas fa-gift fa-2x"></i> </div>
                <h5 class="text-white mb-0">Fashion Combo</h5>
            </div>
            
            <div class="p-4">
                <h5 class="font-size-15 mb-3">Theme 10: Fashion Multi</h5>
                <p class="text-muted mb-3">
                    <i class="far fa-star text-warning me-1"></i> Multi-Product & fashion
                </p>
                <div class="mt-3">
                    <a href="{{ route('campaign.create', ['theme' => 10]) }}" class="btn btn-success waves-effect waves-light w-100">
                        CREATE
                    </a>
                </div>
            </div>

           
        </div>
    </div>
</div>





<div class="col-sm-6 col-xl-3">
    <div class="card">
        <div class="card-body p-0">
            <div class="p-4 text-center text-white" 
                 style="background: linear-gradient(135deg, #0f3d2e 0%, #d4af37 100%); border-radius: 3px 3px 0 0;">
                <div class="font-size-24 mb-3">
                    <i class="fas fa-gift fa-2x"></i> </div>
                <h5 class="text-white mb-0"> Combo</h5>
            </div>
            
            <div class="p-4">
                <h5 class="font-size-15 mb-3">Theme 11: Combo</h5>
                <p class="text-muted mb-3">
                    <i class="far fa-star text-warning me-1"></i> Multi-Combo 
                </p>
                <div class="mt-3">
                    <a href="{{ route('campaign.create', ['theme' => 11]) }}" class="btn btn-success waves-effect waves-light w-100">
                        CREATE
                    </a>
                </div>
            </div>

           
        </div>
    </div>
</div>

        </div>
    </div>
@endsection