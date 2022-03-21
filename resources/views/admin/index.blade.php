@extends('layouts.main')

@section('content')
        {{-- Tag untuk search --}}
        <div class="row">
            <div class="col-auto">
                <a href="{{ route('admin.create') }}" class="btn btn-success mb-3"> Tambah</a>
            </div>
            <form action="?" class="col-auto ms-auto">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                    <button class="btn btn-info" type="submit">Cari</button>
                </div>
            </form>
        </div>
        {{-- End Tag Search --}}

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->nama }}</td>
                        <td>{{ $row->username }}</td>
                        <td>{{ $row->role }}</td>
                        <td>
                            {{-- Btn lihat --}}
                            <a href="{{ route('admin.show', ['admin'=>$row->id]) }}" class="btn btn-info btn-sm">Lihat</a>
                            {{-- End Btn lihat --}}

                            <a href="{{ route('admin.edit', ['admin'=>$row->id]) }}" class="btn btn-info btn-sm">Edit</a>

                            <button onclick="deleteAdmin({{ $row->username }})" class="btn btn-danger btn-sm">Hapus</button>

                            <form id="{{ $row->username }}" hidden action="{{ route('admin.destroy', $row->id) }}" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
@push('js')
<script>
function deleteAdmin(id) {
    let text = "Klik OK untuk hapus!";
    if (confirm(text) == true) {
        id.submit()
    }
}
</script>
@endpush

{{-- a.btn.btn-success.mb-3+table.table.table-bordered>(thead>tr>th*4)+(tbody>tr>td+td+td+td>a.btn.btn-info.btn-sm+button.btn.btn-danger.btn-sm[onClick="deleteAdmin"]+form#[action="" method="post"]) --}}

