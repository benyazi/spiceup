@extends('layouts.viewApp')
@push('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endpush
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endpush
@section('content')
    <screen-view uuid="{{$uuid}}"></screen-view>
@endsection
