@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="alert" aria-label="{{ __('Close') }}">
            <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="alert" aria-label="{{ __('Close') }}">
            <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
        </button>
    </div>
@endif