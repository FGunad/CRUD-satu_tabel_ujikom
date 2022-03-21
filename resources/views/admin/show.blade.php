@extends('layouts.main')

@section('content')
    <div class="card col-5">
        <div class="card-body">
            <p>Nama : {{ $data->nama }}</p>
            <p>Username : {{ $data->username }}</p>
            <p>Role : {{ $data->role }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.index') }}" class="btn btn-info">Kembali</a>
        </div>
    </div>
@endsection
