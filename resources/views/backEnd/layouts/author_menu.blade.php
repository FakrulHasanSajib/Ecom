<style>
    /* ট্যাব মেনু ডিজাইন */
    .custom-tab-menu {
        background: #fff;
        border-radius: 50px;
        padding: 10px 15px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 25px;
        margin-top: 25px;
        gap: 15px;
    }
    .custom-tab-item {
        color: #5a5c69;
        font-weight: 600;
        padding: 8px 25px;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 14px;
        border: 1px solid #e3e6f0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .custom-tab-item:hover {
        background-color: #f8f9fc;
        color: #4e73df;
        text-decoration: none;
    }
    /* একটিভ বাটন ডিজাইন */
    .custom-tab-item.active {
        background-color: #4e73df;
        color: #fff;
        border-color: #4e73df;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }
</style>

<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12">
        <div class="custom-tab-menu">
            
            {{-- ১. Author List --}}
            <a href="{{ route('author.index') }}" 
               class="custom-tab-item {{ request()->routeIs('author.index') ? 'active' : '' }}">
               <i class="fas fa-users"></i> Author List
            </a>

            {{-- ২. Royalty Assign --}}
            <a href="{{ route('loylaty.create') }}" 
               class="custom-tab-item {{ request()->routeIs('loylaty.create') ? 'active' : '' }}">
               <i class="fas fa-hand-holding-usd"></i> Royalty Assign
            </a>

        </div>
    </div>
</div>