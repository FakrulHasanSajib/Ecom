@extends('backEnd.layouts.master')
@section('title', 'Pixel Trigger Setup')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Pixel Event Trigger Settings</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pixel_setup.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">Select When to Fire Purchase Event:</label>
                    
                    <div class="custom-control custom-radio mt-2">
                        <input type="radio" id="triggerAdmin" name="pixel_trigger_type" class="custom-control-input" value="admin" {{ $setting->pixel_trigger_type == 'admin' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="triggerAdmin">
                            When Admin Confirms Order (Default)
                        </label>
                    </div>

                    <div class="custom-control custom-radio mt-2">
                        <input type="radio" id="triggerCustomer" name="pixel_trigger_type" class="custom-control-input" value="customer" {{ $setting->pixel_trigger_type == 'customer' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="triggerCustomer">
                            Directly When Customer Places Order
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-3">Save Change</button>
            </form>
        </div>
    </div>
</div>
@endsection