@extends('layout')

@section('content')
    <h1>Create new site</h1>
    <form action="/sites" method="POST">
        @csrf
        <label for="name">Site name:</label>
        <input type="text" id="name" name="name" value=""/>
        <button type="submit">Save site</button>
    </form>
@endsection
