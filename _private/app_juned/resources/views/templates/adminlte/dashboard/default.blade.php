@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <!-- Main content -->
    <section class="content" id="content-dashboard">
    <div class="overlay">
      <!-- Small boxes (Stat box) -->
      
      <div class="text-welcome">
        Hai<br />
        Selamat Datang di              
        <h3>{!! str_replace('-', '<br/>', config('app.title')) !!}</h3>
      </div>
                        
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $totalPengadaan }}</h3>
              <p>Total Pengadaan</p>
            </div>
            <div class="icon">
              <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ url('pengadaan') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $totalPerbaikan }}</h3>
              <p>Total Perbaikan</p>
            </div>
            <div class="icon">
              <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ url('perbaikan') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $totalProgress }}</h3>
              <p>Progress</p>
            </div>
            <div class="icon">
              <i class="fa fa-gear"></i>
            </div>
            <a href="{{ url('perbaikan') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $totalSelesai }}</h3>
              <p>Selesai</p>
            </div>
            <div class="icon">
              <i class="fa fa-check"></i>
            </div>
            <a href="{{ url('perbaikan') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
      </div><!-- /.row -->
      
    </div>
    </section><!-- /.content -->
<style>
.text-welcome {
    text-align: center;
    font-size: 19px;
    padding: 30px 0;
    
}
</style>
@stop
@push('scripts')

@endpush