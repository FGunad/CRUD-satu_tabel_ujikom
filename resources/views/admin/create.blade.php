@extends('layouts.main')

@section('content')
<h1>Tambah Admin</h1>
<form action="{{ route('admin.store') }}" method="post">
    @csrf
    <x-input name="nama" label="Nama" />
    <x-input name="username" label="Username" />
    <x-input name="password" label="Password" type="password" />
    <x-input name="password_confirmation" label="Konfirmasi Password" type="password" />

    <div class="form-group">
        <label for="">Role</label>
        <select name="role" class="form-select" aria-label="Select Role">
            <option value="admin">Admin</option>
            <option value="resepsionis">Resepsionis</option>
        </select>
    </div>
    <button class="btn btn-success mt-3" type="submit">Save</button>
</form>
@endsection

{{-- form[action="" method="post"]>x-input*4+(div.form-group>label+select.form-select[name="role"]>option*2)+button.btn.btn-success.mt-3[type="submit"] --}}


