@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col">
        <strong>Dashboard</strong>
        <h1 class="h3 mb-3"><strong>Petunjuk & Pratinjau</strong></h1>
    </div>
</div>
<div class="row">
    <div class="card col">
        <div class="card-header">
            <h5 class="mb-3">Petunjuk</h5>
        </div>
        <div class="card-body">
            <ol>
                <li>Menu <b><code>Agenda</code></b> untuk membuat/menjadwalkan Agenda. cukup</li>
                <li>Menu <b><code>Banner</code></b> untuk mengupload, edit, dan hapus Banner</li>
                <li>Pada menu <b><code>Konten</code></b> terdapat submenu seperti Kategori, Subkategori, Isi Konten, dan Galeri Konten</li>
                <li>Pada menu <b><code>Pengaturan</code></b> terdapat beberapa submenu untuk pelengkap Konten</li>
            </ol>
        </div>
    </div>
    <div class="col-8">
        <div class="card pb-5">
            <div class="card-header"><h5>Pratinjau</h5></div>
            <div class="card-body mx-auto text-center p-3 rounded" style="background:#111">
                <iframe class="border-0" src="http://103.27.207.147:5173/" width="720" height="1280"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
