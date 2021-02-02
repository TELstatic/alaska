@section('site_title', formatTitle([__('Installation'), config('app.name')]))

<form action="{{ route('install.qiniu') }}" method="post">
    @csrf

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header">
            <div class="font-weight-medium py-1">{{ __('Qiniu configuration') }}</div>
        </div>

        <div class="card-body">
            @include('shared.message')

            <div class="form-group">
                <label for="qiniu_ak">
                    {{ __('Access Key') }}
                </label>
                <input type="text" name="qiniu_ak" id="qiniu_ak" value="{{ old('qiniu_ak') ?? '' }}"
                       class="form-control{{ $errors->has('qiniu_ak') ? ' is-invalid' : '' }}">
                @if ($errors->has('qiniu_ak'))
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('qiniu_ak') }}</strong>
                            </span>
                @endif
            </div>

            <div class="form-group">
                <label for="qiniu_sk">
                    {{ __('Secret Key') }}
                </label>
                <input type="text" name="qiniu_sk" id="qiniu_sk" value="{{ old('qiniu_sk') ?? '' }}"
                       class="form-control{{ $errors->has('qiniu_sk') ? ' is-invalid' : '' }}">
                @if ($errors->has('qiniu_sk'))
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('qiniu_sk') }}</strong>
                            </span>
                @endif
            </div>

            <div class="form-group">
                <label for="qiniu_bucket">
                    {{ __('Bucket') }}
                </label>
                <input type="text" name="qiniu_bucket" id="qiniu_bucket" value="{{ old('qiniu_bucket') ?? '' }}"
                       class="form-control{{ $errors->has('qiniu_bucket') ? ' is-invalid' : '' }}">
                @if ($errors->has('qiniu_bucket'))
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('qiniu_bucket') }}</strong>
                            </span>
                @endif
            </div>

            <div class="form-group">
                <label for="qiniu_host">
                    {{ __('Host') }}
                </label>
                <input type="text" name="qiniu_host" id="qiniu_host" value="{{ old('qiniu_host') ?? '' }}"
                       class="form-control{{ $errors->has('qiniu_host') ? ' is-invalid' : '' }}">
                @if ($errors->has('qiniu_host'))
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('qiniu_host') }}</strong>
                            </span>
                @endif
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-block btn-primary d-inline-flex align-items-center mt-3 py-2">
        <span class="d-inline-flex align-items-center mx-auto">
            {{ __('Next') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron_left' : 'icons.chevron_right'), ['class' => 'icon-chevron fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])
        </span>
    </button>
</form>
