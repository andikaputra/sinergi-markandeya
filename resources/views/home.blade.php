@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container mt-5">
    <div class="jumbotron text-center bg-primary text-white p-5 rounded">
        <h1 class="display-4">Selamat Datang di Sistem Sinergi KKN, PKL dan PPL ITP Markandeya Bali</h1>
        <p class="lead">Platform untuk pendaftaran, pelaporan, dan monitoring KKN, PKL dan PPL.</p>
        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Masuk</a>
        <a href="{{ route('register.form') }}" class="btn btn-outline-light btn-lg">Daftar</a>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-body text-center">
                    <h5 class="card-title">Registrasi Mudah</h5>
                    <p class="card-text">Daftarkan diri Anda secara online untuk mengikuti program KKN, PKL atau PPL.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-body text-center">
                    <h5 class="card-title">Laporan Harian</h5>
                    <p class="card-text">Isi jurnal harian dengan mudah dan pantau perkembangan kegiatan Anda.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-body text-center">
                    <h5 class="card-title">Monitoring Lokasi</h5>
                    <p class="card-text">Gunakan Google Maps untuk melihat lokasi penugasan dan pengawasan KKN/PKL.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
