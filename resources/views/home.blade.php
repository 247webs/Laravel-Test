@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (Auth::user()->isAdmin())
                        @include('user.list', ['users' => $users])
                    @else
                        @include('transction.list', ['transctions' => Auth::user()->transctions])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
