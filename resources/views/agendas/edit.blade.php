@extends('layouts.master')
{{--
@section('style-addon')
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/calendar-style-new.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/calendar-theme.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/jquery.timepicker.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datepicker.min.css') }}" />
@endsection --}}

@section('content')
<main class="content">
	<div class="container-fluid p-0">

		<div class="row">
			<div class="col">
				<strong>Agenda</strong>
				<h1 class="h3 mb-3"><strong>Edit Agenda</strong></h1>
			</div>
			<div class="col-12 mb-4">
				<a href="{{ route('agenda.index') }}" class="btn btn-outline-secondary"><i class="align-middle" data-feather="chevron-left"></i> Kembali</a>
			</div>
		</div>

		@if(session()->has('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
		  {{ session('success') }}
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		@endif


		<form method="POST" action="{{ route('agenda.update', $agenda->id) }}">
		@method('put')
		@csrf
			<div class="row">
				<div class="col-lg-6 col-6 d-flex mx-auto">
					<div class="card flex-fill">
						<div class="card-body">
							<h2>Edit Agenda</h2>
							<hr />
							<div class="mb-3">
								<div class="row">
									<div class="col-4">
										<label class="form-label fw-bold text-success">Tanggal</label>
										<input type="text" id="tanggal" name="tanggal" class="form-control text-center @error('tanggal') is-invalid @enderror" value="{{ $agenda->tanggal }}" placeholder="31-12-2021" readonly />
										@if ($errors->has('tanggal'))
										<div class="invalid-feedback">
									      {{ $errors->first('tanggal') }}
									    </div>
									    @endif
									</div><!-- / .col -->
									<div class="col-3">
										<label class="form-label fw-bold text-success">Waktu</label>
										<input type="text" name="waktu" class="form-control waktu text-center @error('waktu') is-invalid @enderror" autocomplete="off" placeholder="00:00" required value="{{ $agenda->waktu }}" />
										@if ($errors->has('waktu'))
										<div class="invalid-feedback">
									      {{ $errors->first('waktu') }}
									    </div>
									    @endif
									</div><!-- / .col -->
									<div class="col-5">
										<label class="form-label fw-bold text-success">Lokasi</label>
										<input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" placeholder="Cth: Kota Bekasi" value="{{ $agenda->lokasi }}" />
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
								<input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukan judul agenda" value="{{ $agenda->judul }}" autocomplete="off" required>
								@if ($errors->has('judul'))
								<div class="invalid-feedback">
							      {{ $errors->first('judul') }}
							    </div>
							    @endif
							</div><!-- / .mb-3 -->
							<div class="mb-3">
								<label class="form-label fw-bold text-success">Keterangan</label>
								<textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" placeholder="Tulis keterangan singkat tentang agenda hari ini" required>{{ $agenda->keterangan }}</textarea>
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

	</div>
</main>
@endsection

{{-- @section('script-addon')
<script type="text/javascript" src="{{ asset('assets/js/calendar.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/datepicker.min.js') }}"></script>
@endsection --}}

@section('js')

function selectDate(date) {
  $('.calendar-container').updateCalendarOptions({
    date: date
  });
  $('#tanggal').val(moment(date).format('DD-MM-YYYY'));
  getTanggal = moment(date).format('Do MMMM YYYY');
}
$('.calendar-container').calendar({
	date:new Date(),
	prevButton: `Prev`,
	nextButton: `Next`,
	highlightSelectedWeekday:false,
	highlightSelectedWeek:true,
	weekDayLength: 3,
	todayButtonContent:"Hari ini",
	onClickDate: selectDate

});
$('.waktu').timepicker({
	'timeFormat': 'H.i',
	'step': 15,
	'scrollDefault': 'now'
});
$('#tanggal').datepicker({
    format: 'dd-mm-yyyy',
    //trigger: '#openDate',
    autoHide: true
});

@endsection
