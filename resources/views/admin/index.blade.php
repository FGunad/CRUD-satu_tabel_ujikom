@extends('layouts.main')

@section('content')
        <a href="{{ route('admin.create') }}" class="btn btn-success mb-3">Tambah</a>
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
