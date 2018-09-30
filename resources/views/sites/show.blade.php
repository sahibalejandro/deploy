@extends('layout')

@section('content')
    <h1>Site: {{ $site->name }}</h1>
    <div>
        <strong>Repository:</strong>
        {{ $site->repository }}
    </div>

    <form action="/sites/{{ $site->id }}" method="POST">
        @method('DELETE')
        @csrf
        <button type="submit">Delete site</button>
    </form>
@endsection
