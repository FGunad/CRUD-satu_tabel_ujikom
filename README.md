# CRUD Satu tabel **admin**
Membuat CRUD satu tabel admin dengan struktur tabel sesuai dengan soal ujikom hotel menggunakan laravel 8

## Instalasi
### Buat project baru
```php
composer create-project laravel/laravel:8.6 nama_folder
```
**nama_folder** disesuaikan dengan keinginan!

### Menambahkan bootstrap 5.1

Download bootstrap di [Bootstrap](https://getbootstrap.com/docs/5.1/getting-started/download/) *klik button* **download**.
Extrak file lalu pindahkan ke folder **public**

contoh :

![bootstrap](https://user-images.githubusercontent.com/85858049/159110821-f8dd2c25-8c80-4166-9d39-32bd938f51c7.PNG)


### Buat Model, Migration, dan Controller Admin
```php
php artisan make:model Admin -mc --resource
```

### Connect kepada database
Mengubah koneksi ke database pada file **.env** pada bagian ini sesusai database anda :

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### Setup Model Admin
Menambahkan **fillable**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'username', 'password', 'role'
    ];
}
```

### Setup Migration Admin
tambah field yang dibutuhkan pada table admin di class **create_admins_table** pada method **up()**.

```php
public function up()
{
    Schema::create('admins', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('username')->unique();
        $table->string('password');
        $table->enum('role', ['admin', 'resepsionis'])->default('resepsionis');
        $table->rememberToken();
        $table->timestamps();
    });
}
```

###  Run Migrate
Jalankan migration dengan perintah artisan :
```php 
php artisan migrate:fresh
```

### Setup Route
Rubah **Route::get /** dan buat route resource admin pada file **routes/web.php**.

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

...................................
...................................

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Route::resource('admin', AdminController::class);

```

### Setup AdminController

Buka file AdminController lalu sesuaikan dengan kodingan berikut :

```php
<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Admin::all();
        return view('admin.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:admins',
            'password' => 'required|confirmed',
            'role' => 'required'
        ]);

        Admin::create($request->all());

        return redirect()->route('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('admin.edit', ['admin'=>$admin]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama' => 'required',
            'username' => "required|unique:admins,username,{$admin->id}"
        ]);

        if ($request->password) {
            $array = [
                'nama'=>$request->nama,
                'username'=>$request->username,
                'password'=>bcrypt($request->password),
            ];
        } else {
            $array = [
                'nama'=>$request->nama,
                'username'=>$request->username,
            ];
        }
        $admin->update($array);

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return back();
    }
}

```

### Create Views

- layouts/main.blade.php

    ```html
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Admin</title>
        <link rel="stylesheet" href="{{ url('/bootstrap-5.1.3-dist/css/bootstrap.min.css') }}">
    </head>

    <body>
        <div class="container content mt-5">
            @yield('content')
        </div>
        <script src="{{ url('/bootstrap-5.1.3-dist/js/bootstrap.min.js') }}"></script>
        @stack('js');
    </body>

    </html>
    ```
- admin/index.blade.php

    ```php
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

    ```

- components/input.blade.php

    ```html
    @props(['label', 'name', 'type'=>'text', 'value'=>''])
    <div class="form-group">
        <Label>{{ $label }}</Label>
        <input
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name,$value) }}"
        class="form-control{{ $errors->has($name) ? ' is-invalid' : ''}}"
        >
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    ```

- admin/create.blade.php

    ```html
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

    ```

- admin/edit.blade.php
    ```html
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

    ```


### Run Server
```php
 php artisan serve
```

### Buka di browser
Buka di browser dengan url [http://localhost:8000](http://localhost:8000)

<br>
<br>
<br>
<br>

## Menambahkan Search dan Show Data


![Screenshot (50)](https://user-images.githubusercontent.com/85858049/159263481-689291ce-813c-45a9-be4a-1fd83217b204.png)


### Edit file **admin/index.blade.php**

Disini saya merubah code di atas tag **table**. menambahkan form untuk search
```php
...................................
@section('content')

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

<table class="table table-bordered">
...................................
```

Kemudian saya menambahkan button **lihat** diatas button **edit**. 

code berikut :

```php
<a href="{{ route('admin.show', ['admin'=>$row->id]) }}" class="btn btn-info btn-sm">Lihat</a>
```

Maka code file **admin/index.blade.php** menjadi :

```php
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

                            <button onClick="deleteAdmin({{ $row->username }})" class="btn btn-danger btn-sm">Hapus</button>

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

```

### Edit file **AdminController**
Saya merubah pada function **index**.

code brikut :

```php
public function index(Request $request)
{
    $cari = $request->search;

    $data = Admin::when($cari, function($query, $cari){
        return $query->where('nama', 'like', "%{$cari}%");
    })->get();
    return view('admin.index', ['data' => $data]);
}
```

Kemudian Saya merubah function **show**.

code berikut :

```php
public function show(Admin $admin)
{
    return view('admin.show', ['data'=>$admin]);
}
```

Maka code file **AdminController.php** menjadi :

```php
<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cari = $request->search;

        $data = Admin::when($cari, function($query, $cari){
            return $query->where('nama', 'like', "%{$cari}%");
        })->get();
        return view('admin.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:admins',
            'password' => 'required|confirmed',
            'role' => 'required'
        ]);

        Admin::create($request->all());

        return redirect()->route('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return view('admin.show', ['data'=>$admin]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        return view('admin.edit', ['admin'=>$admin]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama' => 'required',
            'username' => "required|unique:admins,username,{$admin->id}"
        ]);

        if ($request->password) {
            $array = [
                'nama'=>$request->nama,
                'username'=>$request->username,
                'password'=>bcrypt($request->password),
            ];
        } else {
            $array = [
                'nama'=>$request->nama,
                'username'=>$request->username,
            ];
        }
        $admin->update($array);

        return redirect()->route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return back();
    }
}

```

### Membuat file views baru **admin/show.blade.php**

Code berikut :

```php
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

```

**Rubah tampilan sesuai keinginan !**



## Menambahkan Alert
### Menambahkan file **componets/status.blade.php**
code berikut :

```php
@if (session('status')=='store')
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Berhasil Simpan!</strong> Data telah berhasil di simpan.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('status')=='update')
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Berhasil Update!</strong> Data telah berhasil di ubah.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('status')=='destroy')
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Berhasil Hapus!</strong> Data telah berhasil di hapus.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

```

### Menambah kan component **status** di file **admin/index.blade.php** 

```php

@section('content')
<x-status />

...................................

```

### Merubah file **AdminController.php**

menambahkan function **with** di setiap **return store, update, dan destroy**

function store menjadi :

```php
return redirect()->route('admin.index')->with('status', 'store');
```
function update menjadi :

```php
return redirect()->route('admin.index')->with('status', 'update');
```
function destroy menjadi :

```php
return back()->with('status', 'destroy');
```



<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

# Perintah Artisan

### membuat model
```sh
php artisan make:model NamaModel
```

### membuat Migration
```sh
php artisan make:migration NamaMigration
```
 
### membuat Controller
```sh
php artisan make:controller NamaController
```
 