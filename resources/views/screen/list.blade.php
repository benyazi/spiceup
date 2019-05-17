@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">List of screens</div>
                    <div class="card-body">
                        <div>
                            <a href="{{route('screen.add')}}">+ Add screen</a>
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
