@extends('layouts.app')
@push('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endpush
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">List of screens</div>
                    <div class="card-body">
                        <div>
                            <a class="btn btn-primary" href="{{route('screen.add')}}">+ Add screen</a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>UUID</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $screen)
                                    <tr>
                                        <td>{{$screen->id}}</td>
                                        <td>{{$screen->uuid}}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{route('screen.edit', ['id' => $screen->id])}}">
                                                Edit
                                            </a>
                                            <a class="btn btn-danger" href="{{route('screen.remove', ['id' => $screen->id])}}">
                                                Remove
                                            </a>
                                            <a class="btn btn-info" target="_blank" href="{{env('APP_URL').'/s/'.$screen->uuid}}">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
