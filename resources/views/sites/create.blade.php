@extends('layout')

@section('content')
    <h1>Create new site</h1>
    <form action="/sites" method="POST">
        @csrf

        <div>
            <label for="name">Site name:</label>
            <input type="text" id="name" name="name" value=""/>
        </div>

        <div>
            <label for="repository">Repository:</label>
            <input type="text" id="repository" name="repository" value=""/>
        </div>

        <button type="submit">Save site</button>
    </form>
@endsection
