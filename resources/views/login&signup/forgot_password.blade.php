@extends('layout.index')
@section('content')
    <div class="container m-5 p-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('fail'))
                            <div class="alert alert-danger">{{ session('fail') }}</div>
                        @endif
                        <form action="{{ route('send_code') }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Reset Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
