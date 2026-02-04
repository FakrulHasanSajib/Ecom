@extends('backEnd.layouts.master')
@section('title','Teamplate create')
@section('css')
<link href="{{asset('backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('sms.index')}}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Tempate Create</h4>
            </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('sms.store')}}" method="POST" class=row data-parsley-validate=""  enctype="multipart/form-data">
                    @csrf 
                    
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Template Type</label>
                            <input name="type" class="form-control @error('type') is-invalid @enderror"  required />
                            
                            
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label for="roles" class="form-label">Template Content</label>
                            <textarea name="templateContent" id="templateContent" class="form-control" rows="6" placeholder="Write your message here"></textarea>
                            <small id="wordCountMessage" class="text-muted"></small>

                             

                            @error('templateContent')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- col end -->
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <div class="form-check form-switch mb-3">
                          <input class="form-check-input" type="checkbox" id="dynamicsms" name="dynamicsms">
                          <label class="form-check-label" for="dynamicsms">Dynamic Sms Active</label>
                        </div>


                             

                            @error('dynamicsms')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    
                    <!-- col end -->
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection


@section('script')
<script src="{{asset('backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const textarea = document.getElementById('templateContent');
        const message = document.getElementById('wordCountMessage');
        const maxUnits = 160;

        textarea.addEventListener('input', function (e) {
            const units = [...textarea.value];

            if (units.length >= maxUnits) {
                textarea.value = units.slice(0, maxUnits).join("");
                textarea.disabled = true;
                message.textContent = "Limit reached (160 characters)";
                message.style.color = "red";
            } else {
                message.textContent = `Count: ${units.length}/${maxUnits}`;
                message.style.color = "gray";
            }
        });

        // Listen for keydown globally in case textarea is disabled
        document.addEventListener('keydown', function (e) {
            if (textarea.disabled && e.key === 'Backspace') {
                e.preventDefault();

                // Get current value and remove the last character
                let value = textarea.value;
                textarea.value = value.slice(0, -1);

                textarea.disabled = false;

                // Update count display
                const units = [...textarea.value];
                message.textContent = `Count: ${units.length}/${maxUnits}`;
                message.style.color = "gray";

                // Move focus back to textarea
                textarea.focus();
            }
        });
    });
    
    
</script>





@endsection