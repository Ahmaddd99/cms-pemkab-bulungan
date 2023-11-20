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
                <li>Halaman <b><code>Agenda</code></b> digunakan untuk membuat agenda kegiatan</li>
                <li>Halaman <b><code>Banner</code></b> digunakan untuk menambah atau menghapus banner pada tampilan utama di kiosk</li>
                <li>Pada menu <b><code>Konten</code></b> terdapat beberapa submenu untuk menampilkan konten pada tampilan utama
                    <ul>
                        <li><strong>Kategori.</strong> Halaman ini digunakan untuk membuat, mengedit dan mengahapus Kategori pada halaman utama</li>
                        <li><strong>Subkategori.</strong> Halaman ini digunakan untuk menambah atau menghapus Subkategori</li>
                        <li><strong>Isi Konten.</strong> Halaman ini digunakan untuk menambah atau menghapus Isi Konten pada tampilan di kiosk</li>
                        <li><strong>Galeri Konten.</strong> Halaman ini digunakan untuk menambah atau menghapus Galeri Konten di halaman konten</li>
                    </ul>
                </li>
                <li>Pada menu <b><code>Pengaturan</code></b> terdapat beberapa submenu untuk pelengkap Konten
                    <ul>
                        <li><strong>Fitur.</strong> Halaman ini digunakan untuk membuat, mengedit dan mengahapus Fitur pada halaman utama</li>
                        <li><strong>Rating.</strong> Halaman Label ini digunakan untuk menambah atau menghapus Label didalam Isi Konten</li>
                        <li><strong>Label.</strong> Halaman Rating ini digunakan untuk menambah atau menghapus Rating didalam Isi Konten</li>
                    </ul>
                </li>
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
