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
            <div class="col-12 col-md-8 text-right">
                <div class="mb-3">
                    <a class="btn btn-primary" href="{{route('screen.add')}}">
                        <i class="fa fa-plus"></i> {{ __('Add screen') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>UUID</th>
                                <th class="text-right">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $screen)
                                    <tr>
                                        <td>{{$screen->id}}</td>
                                        <td>{{$screen->uuid}}</td>
                                        <td class="text-right">
                                            <a title="{{ __('View screen') }}" class="btn btn-primary btn-sm" target="_blank" href="{{env('APP_URL').'/s/'.$screen->uuid}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a title="{{ __('Edit screen') }}" class="btn btn-primary btn-sm" href="{{route('screen.edit', ['id' => $screen->id])}}">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <a title="{{ __('Remove screen') }}" class="btn btn-danger btn-sm" href="{{route('screen.remove', ['id' => $screen->id])}}">
                                                <i class="fa fa-trash-alt"></i>
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
