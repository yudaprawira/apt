@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title"> {{ val($dataForm, 'form_title') }} </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  
                  <form method="POST" action="{{ BeUrl(config('perbaikan.info.alias').'/save') }}">
                    

                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="form-group has-feedback">
                                <label>Judul Perbaikan</label>
                                <span class="form-control" disabled>{{ val($dataForm, 'judul') }}</span>
                                <input type="hidden" name="judul" value="{{ val($dataForm, 'judul') }}" />
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>Tanggal Permintaan</label>
                                        <span class="form-control" disabled style="padding-right: 0;">{{ formatDate(val($dataForm, 'tgl_permintaan'), 6) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>Status Pekerjaan</label>
                                        <select name="status_kerja" class="form-control">
                                            @foreach( config('perbaikan.status') as $k=>$v )
                                            @if($v!='BARU')
                                            <option value="{{ $k }}" {{ val($dataForm, 'status_kerja')==$k ? 'selected' : '' }} >{{ $v }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>Tanggal Selesai</label>
                                        <input type="text" class="form-control tDateTime" name="tgl_selesai" maxlength="20" value="{{ val($dataForm, 'tgl_selesai') ? formatDate(val($dataForm, 'tgl_selesai'), 6) : '' }} " />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Keterangan</label><span class="char_count"></span>
                                <textarea name="keterangan" class="form-control">{!! val($dataForm, 'keterangan') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Jenis Permintaan</label>
                                <span class="form-control" disabled>{{ val($dataForm, 'jenis_permintaan') }}</span>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Pelanggan</label>
                                <?php
                                    $pel = \Modules\Pelanggan\Models\Pelanggan::where('status', 1)->whereIn('id', explode(',', val($dataForm, 'id_pelanggan')))->first();
                                ?>
                                <address style="background: #eee;border: 1px solid #d2d6de;padding: 10px;">
                                    <strong> {{ val($pel, 'nama') }} </strong><br/>
                                    <p>{{ val($pel, 'alamat') }} - {{ val($pel, 'kota') }}</p>
                                    <p>{{ val($pel, 'telepon') }} </p>
                                    <p>{{ val($pel, 'email') }}</p>
                                </address>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Catatan Permintaan</label>
                                <address style="background: #eee;border: 1px solid #d2d6de;padding: 10px;">
                                    {!! val($dataForm, 'permintaan') !!}
                                </address>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Teknisi</label>
                                @if ( val($dataForm, 'relpenanganan') )
                                <ul style="background: #eee;border: 1px solid #d2d6de;padding: 10px 30px;">
                                @foreach( json_decode(formatTokenInput(val($dataForm, 'relpenanganan'), 'id', 'username'), true) as $u )
                                    <li>{{ val($u, 'name') }}</li>
                                @endforeach
                                </ul>
                                @endif
                            </div>
                            
                        </div>
                    </div>

                    
                    <input type="hidden" name="id" value="{{ val($dataForm, 'id') }}" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button type="submit" class="btn btn-primary btn-flat">{{ val($dataForm, 'id') ? trans('global.act_edit') : trans('global.act_add') }}</button>
                    <a href="{{ BeUrl(config('perbaikan.info.alias')) }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_back') }}</a>
                  </form>
                  
                </div><!-- /.box-body -->
              </div>
        </div>
    </div>
@stop

@push('scripts')
<script src="{{ asset('/global/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
$(document).ready(function(){
    
    initTokenInput('#id_pelanggan', 1);
    initTokenInput('#id_teknisi');

});
tinymce.init({ 
    selector:'textarea',
    height: 300,
    theme: 'modern', 
    plugins: [
        'lists link image',
        'searchreplace wordcount visualblocks visualchars fullscreen',
        'media',
        'paste textcolor colorpicker textpattern imagetools'
    ],
    toolbar1: 'bold italic | alignleft aligncenter alignright alignjustify | bullist numlist forecolor backcolor | link image',
    image_advtab: true,
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
    ],
    //autoresize_bottom_margin: 50,
    menubar:false,
    statusbar: false,
});
</script>
@endpush