@extends('layouts.main')

@section('content')
<h1>Edit Admin</h1>
<form action="{{ route('admin.update', ['admin'=>$admin->id]) }}" method="post">
    @method('PUT')
    @csrf
    <x-input name="nama" label="Nama" :value="$admin->nama" />
    <x-input name="username" label="Username" :value="$admin->username" />
    <x-input name="password" label="Password" type="password" />
    <x-input name="password_confirmation" label="Konfirmasi Password" type="password" />
    <button class="btn btn-success mt-3" type="submit">Update</button>
</form>
@endsection

{{-- form[action method="post"]>x-input*4+button.btn.btn-success.mt-3[type="submit"] --}}
