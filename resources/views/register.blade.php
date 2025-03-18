@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Form Pendaftaran Mahasiswa</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"  placeholder="Masukkan nama Anda" required>
                        </div>

                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" name="nim" class="form-control" placeholder="Masukkan nim Anda" required>
                        </div>

                        <div class="mb-3">
                            <label for="kampus" class="form-label">Kampus</label>
                            <select name="kampus" class="form-select" required>
                                <option value="ITP Markandeya Bali">ITP Markandeya Bali (Pusat)</option>
                                <option value="PKMB Tabanan">PKMB Tabanan</option>
                                <option value="PKMB Widyagiri Petang">PKMB Widyagiri Petang</option>
                                <option value="PKBM Abiansemal">PKBM Abiansemal</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kegiatan" class="form-label">Jenis Kegiatan</label>
                            <select name="kegiatan" class="form-select" required>
                                <option selected >--Pilih Kegiatan--</option>
                                <option value="KKN">KKN</option>
                                <option value="PKL">PKL</option>
                                <option value="PKL">PKL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Lokasi Kecamatan</label>
                            <select name="kecamatan" class="form-select" required>
                                <option selected >--Pilih Kecamatan--</option>
                                <option disabled >--Kabupaten Bangli--</option>
                                <option value="Bangli">Bangli</option>
                                <option value="Kintamani">Kintamani</option>
                                <option value="Susut">Susut</option>
                                <option disabled >--Kabupaten Karangasem--</option>
                                <option value="Abang">Abang</option>
                                <option value="Bebandem">Bebandem</option>
                                <option value="Karangasem">Karangasem</option>
                                <option value="Kubu">Kubu</option>
                                <option value="manggis">Manggis</option>
                                <option value="Rendang">Rendang</option>
                                <option value="Sidemen">Sidemen</option>
                                <option disabled >--Kabupaten Gianyar--</option>
                                <option value="Blabatuh">Blabatuh</option>
                                <option value="Gianyar">Gianyar</option>
                                <option value="Payangan">Payangan</option>
                                <option value="Sukawati">Sukawati</option>
                                <option value="Tampak Siring">Tampak Siring</option>
                                <option value="Tegalalang">Tegalalang</option>
                                <option value="Ubud">Ubud</option>
                                <option disabled >--Kabupaten Kelungkung--</option>
                                <option value="Banjarangkan">Banjarangkan</option>
                                <option value="Dawan">Dawan</option>
                                <option value="Klungkung">Klungkung</option>
                                <option value="Nusa Penida">Nusa Penida</option>
                                <option disabled >--Kabupaten Badung--</option>
                                <option value="Petang">Petang</option>
                                <option value="Abiansemal">Abiansemal</option>
                                <option value="Penebel">Penebel</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Program Studi</label>
                            <select name="prodi" class="form-select" required>
                                <option selected >--Pilih Prodi--</option>
                                <option value="PGSD">S1 Pendidikan Guru Sekolah Dasar</option>
                                <option value="PBSI">S1 Pendidikan Bahasa dan Sastra Indonesia</option>
                                <option value="PBI">S1 Pendidikan Bahasa Inggris</option>
                                <option value="SI">S1 Sistem Informasi</option>
                                <option value="ME">S1 Manajemen Ekonomi</option>
                                <option value="PARBUD">S1 Pariwisata Budaya Dan Keagamaan</option>
                                <option value="HUKUM">S1 Hukum Adat</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="pembayaranKRS" class="form-label">Link Bukti Pembayaran KRS</label>
                            <input type="text" name="pembayaranKRS" class="form-control" placeholder="Masukkan Link Bukti Pembayaran KRS" required>
                        </div>

                        <div class="mb-3">
                            <label for="KRS" class="form-label">Link KRS</label>
                            <input type="text" name="KRS" class="form-control" placeholder="Masukkan Link KRS"required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan Email anda" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center">
                    <small>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
