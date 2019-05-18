@extends('layouts.app')
@push('scripts')
    <script src="{{ asset('js/screenDashboard/app.js') }}" defer></script>
@endpush
@push('styles')
    <link href="{{ asset('css/screenDashboard/screenDashboard.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                {{$screen->name}}
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-info" target="_blank" href="{{env('APP_URL').'/s/'.$screen->uuid}}">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="screen-dashboard-app">
                            <screen-dashboard uuid="{{$screen->uuid}}"></screen-dashboard>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
