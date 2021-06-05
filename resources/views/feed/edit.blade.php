@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Feed') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{$error}}</div>
                        @endforeach
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('feed.update', $feed->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $feed->name) }}" required autocomplete="false">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="text" class="col-md-2 col-form-label text-md-right">{{ __('url') }}</label>

                            <div class="col-md-10">
                                <input id="url" type="url" value="{{ old('url', $feed->url) }}" class="form-control @error('url') is-invalid @enderror" name="url" required autocomplete="false">

                                @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="is_notify" class="col-md-2 col-form-label text-md-right">{{ __('Email Notify') }}</label>

                            <input type="hidden" name="is_notify" value="0">


                            <div class="col-md-10">
                                <input id="is_notify" type="checkbox" name="is_notify" value="1" @if(old('is_notify', $feed->is_notify) == '1')) checked @endif autocomplete="false">
                                <label for="is_notify" class="col-form-label ml-4">{{ __('Once in hour') }}</label>
                            </div>
                        </div>

                        <br/>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
