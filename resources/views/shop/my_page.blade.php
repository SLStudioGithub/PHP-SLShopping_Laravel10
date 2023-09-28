@extends('layouts.shop_layout')
@section('title', 'shop my_page')
@include('shop.header')
@section('content')
<article class="container">
    <div>
        <header>
            <h1 align="center">My Page</h1>
        </header>
        <section>
            <table class="table table-striped">
                <thead></thead>
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ Auth::user()->id }}</td>
                    </tr>
                    <tr>
                        <th>User Name</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ Auth::user()->created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    <div>
</article>
@endsection
@include('shop.footer')