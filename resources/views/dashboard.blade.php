@extends('layout')

@section('content')
    <h1>Dashboard</h1>

    <h2>Sites</h2>
    <a href="/sites/create">Add new site</a>
    <ul>
        @foreach ($sites as $site)
            <li><a href="/sites/{{ $site->id }}">{{ $site->name }}</a></li>
        @endforeach
    </ul>
@endsection
