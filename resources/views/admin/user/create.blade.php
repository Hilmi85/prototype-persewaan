@extends('admin.layouts.master')
@section('title', 'Tambah User')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Tambah User</h3>
            <p class="text-muted mb-0">
                Tambahkan data pengguna baru ke sistem Quin Salon.
            </p>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Submit Error!</h5>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form class="form" action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="fullname">Nama Lengkap</label>
                            <input type="text" class="form-control" id="fullname"
                                   placeholder="Masukkan Nama Lengkap" name="fullname"
                                   value="{{ old('fullname') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username"
                                   placeholder="Masukkan Username" name="username"
                                   value="{{ old('username') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone"
                                   placeholder="Masukkan Nomor Telepon" name="phone"
                                   value="{{ old('phone') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email"
                                   placeholder="Masukkan Email" name="email"
                                   value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="address">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3"
                                      placeholder="Masukkan Alamat">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role_id" required>
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password"
                                   placeholder="Masukkan Password" name="password" required>
                            <small><a href="#" class="toggle-password" data-target="password">Lihat Password</a></small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   placeholder="Masukkan Konfirmasi Password" name="password_confirmation" required>
                            <small><a href="#" class="toggle-password" data-target="password_confirmation">Lihat Password</a></small>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary me-1 mb-1">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            let input = document.getElementById(this.dataset.target);
            let isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            this.textContent = isHidden ? 'Sembunyikan Password' : 'Lihat Password';
        });
    });
</script>
@endsection
