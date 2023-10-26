@extends('layouts.master')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col">
                    <strong>Agenda</strong>
                    <h1 class="h3 mb-3"><strong>Daftar Agenda</strong></h1>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <form method="POST" action="{{ route('agenda.post') }}">
                @csrf
                <div class="row">
                    <div class="col-6 col-lg-6 col-xxl-9 d-flex">
                        <div class="card flex-fill">
                            <div class="calendar-container"></div>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-lg-6 col-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <h5>Tambah Agenda</h5>
                                <hr />
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fw-bold text-success">Tanggal</label>
                                            <input type="text" id="tanggal" name="tanggal"
                                                class="form-control text-center @error('tanggal') is-invalid @enderror"
                                                style="" placeholder="" readonly>
                                            @if ($errors->has('tanggal'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('tanggal') }}
                                                </div>
                                            @endif
                                        </div><!-- / .col -->
                                        <div class="col-3">
                                            <label class="form-label fw-bold text-success">Waktu</label>
                                            <input type="text" name="waktu"
                                                class="form-control waktu text-center @error('waktu') is-invalid @enderror"
                                                autocomplete="off" placeholder="00:00" required
                                                value="{{ old('waktu') }}" />
                                            @if ($errors->has('waktu'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('waktu') }}
                                                </div>
                                            @endif
                                        </div><!-- / .col -->
                                        <div class="col-5">
                                            <label class="form-label fw-bold text-success">Lokasi</label>
                                            <input type="text" name="lokasi"
                                                class="form-control @error('lokasi') is-invalid @enderror"
                                                placeholder="Cth: Kota Bekasi" value="{{ old('lokasi') }}" />
                                            @if ($errors->has('lokasi'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('lokasi') }}
                                                </div>
                                            @endif
                                        </div><!-- / .col -->
                                    </div><!-- / .row -->
                                </div><!-- / .mb-3 -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-success">Judul Agenda</label>
                                    <input type="text" name="judul"
                                        class="form-control @error('judul') is-invalid @enderror"
                                        placeholder="Masukan judul agenda" value="{{ old('judul') }}" autocomplete="off"
                                        required>
                                    @if ($errors->has('judul'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('judul') }}
                                        </div>
                                    @endif
                                </div><!-- / .mb-3 -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-success">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                        placeholder="Tulis keterangan singkat tentang agenda hari ini" required>{{ old('keterangan') }}</textarea>
                                    @if ($errors->has('keterangan'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('keterangan') }}
                                        </div>
                                    @endif
                                </div><!-- / .mb-3 -->
                                <div class="mb-3 mt-4 d-flex justify-content-end">
                                    <button class="btn btn-success">Simpan</button>
                                </div><!-- / .mb-3 -->
                            </div><!-- / .card-body -->
                        </div>
                    </div><!-- / .col -->
                </div><!-- / .row -->
            </form>

            <div class="row">
                <div class="col">
                    <h4 class=" mb-3"><strong>Agenda: <span class="tanggalPilih"></span></strong></h4>
                </div>
            </div><!-- / .row -->


            <section id="agenda">
                <div class="row">
                    <div class="col-12">

                    </div><!-- / .col -->
                </div><!-- / .row -->
            </section>

        </div>
    </main>
@endsection
@section('js')
    $.ajaxSetup({ cache: false });
		moment.locale('id');
		var dateHariIni = new Date();
		var agendaHariIni = moment(dateHariIni).format('Do MMMM YYYY');
	    var formatTanggalHariIni = moment(dateHariIni).format("DD-MM-YYYY");
	    var getTanggal = moment(dateHariIni).format('Do MMMM YYYY');

	 //    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		// var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		//   return new bootstrap.Tooltip(tooltipTriggerEl)
		// });
		$('[data-toggle="tooltip"]').tooltip();

    $('#tanggal').val(moment(new Date()).format('DD-MM-YYYY'));
    $('.tanggalPilih').html(agendaHariIni);
    getAgenda(formatTanggalHariIni);

    function selectDate(date) {
        $('.calendar-container').updateCalendarOptions({
            date: date
        });
        $('#tanggal').val(moment(date).format('DD-MM-YYYY'));
        $('.tanggalPilih').html(moment(date).format('Do MMMM YYYY'));
        getTanggal = moment(date).format('Do MMMM YYYY');
        var formatTanggal = moment(date).format("DD-MM-YYYY");
        getAgenda(formatTanggal);
    }
    $('.calendar-container').calendar({
        date: new Date(),
        prevButton: `Prev`,
        nextButton: `Next`,
        highlightSelectedWeekday: false,
        highlightSelectedWeek: true,
        weekDayLength: 3,
        todayButtonContent: "Hari ini",
        onClickDate: selectDate

    });
    $('.waktu').timepicker({
        'timeFormat': 'H.i',
        'step': 15,
        'scrollDefault': 'now'
    });

    function getAgenda(tanggal) {
        const api_agenda = "{{ route('agenda.all') }}";
        $.getJSON(api_agenda, function(result) {
            var fetchAgenda = result;
            var data = fetchAgenda.filter(a => a.tanggal === tanggal);
            if (data.length === 0) {
                $('section#agenda').html(`
                <div class="row">
                    <div class="col text-center">
                        <div class="card mb-3 text-center">
                            <div class="no-agenda-found text-muted">
                                <p>Tidak ada agenda hari ini.</p>
                            </div>
                        </div><!-- / .card -->
                    </div>
                </div>
                `);
            } else {
                var output = '<div class="row"><div class="col">';
                $.each(data, function(key, val) {
                output += `<div class="card mb-3">
                    <div class="row">
                        <div class="col-2 text-center my-auto col-agenda-jam">
                            <div class="agenda-jam text-success">${val.waktu}</div>
                        </div><!-- / .col -->
                        <div class="col">
                            <div class="card-body">
                                <h2 class="card-title agenda-title">${val.judul}</h2>
                                    <p class="card-text agenda-description">${val.keterangan}</p>
                                        <div class="agenda-lokasi text-success">${val.lokasi}</div>
                             </div><!-- / .card-body -->
                        </div><!-- / .col -->
                        <div class="col-2 d-flex justify-content-center align-items-center">
                            <a href="agenda/edit/${val.id}" class="btn btn-success">Edit</a>
                            <form method="POST" action="agenda/delete/${val.id}" class="d-inline">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger" style="margin-left:10px" onclick="return confirm('Anda yakin ingin menghapus agenda di tanggal ini?')">&times;</button>
                            </form>
                        </div><!-- / .col -->
                    </div><!-- / .row -->
                    </div><!-- / .card -->`;
                }) // END OF EACH FUNCTION
                output += '</div></div>';
                $('section#agenda').html(output);
            }
        }); // END OF GETJSON
    }
@endsection
