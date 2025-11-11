@extends('layouts.master')

@section('content')
<div class="container" style="max-width:480px">
    <h2 class="mb-3">Đăng nhập</h2>

    <form method="post" action="/login">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary">Đăng nhập</button>
            <a href="/" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
