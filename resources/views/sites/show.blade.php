@extends('layout')

@section('content')
    <h1>Site: {{ $site->name }}</h1>

    <form action="/sites/{{ $site->id }}" method="POST">
        @method('DELETE')
        @csrf
        <button type="submit">Delete site</button>
    </form>
@endsection
