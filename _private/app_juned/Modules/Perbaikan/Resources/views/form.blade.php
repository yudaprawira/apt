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
                    
                    <div class="form-group has-feedback">
                        <input type="checkbox" name="status" {{ isset($dataForm['status']) ? (val($dataForm, 'status')=='1' ? 'checked' : '') : 'checked' }} /> {{ trans('global.status_active') }}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Judul Perbaikan</label><span class="char_count"></span>
                                <input type="text" class="form-control" name="judul" maxlength="125" value="{{ val($dataForm, 'judul') }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Pelanggan</label>
                                <input type="text" class="form-control" id="id_pelanggan" name="id_pelanggan" value="{{ val($dataForm, 'id_pelanggan') }}" data-populated="{{ val($dataForm, 'id_pelanggan') ? formatTokenInput(\Modules\Pelanggan\Models\Pelanggan::where('status', 1)->whereIn('id', explode(',', val($dataForm, 'id_pelanggan')))->get(), 'id', 'nama') : '{}' }}" data-source="{{ BeUrl(config('pelanggan.info.alias').'/lookup') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Teknisi</label>
                                <input type="text" class="form-control" id="id_teknisi" name="id_teknisi" data-populated="{{ val($dataForm, 'relpenanganan') ? formatTokenInput(val($dataForm, 'relpenanganan'), 'id', 'username') : '{}' }}" data-source="{{ BeUrl('system-user/lookup') }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>Tanggal Permintaan</label>
                                        <input type="text" class="form-control tDate" name="tgl_permintaan" maxlength="20" value="{{ formatDate(val($dataForm, 'tgl_permintaan'), 2) }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>Status Pekerjaan</label>
                                        <select name="status_kerja" class="form-control">
                                            @foreach( config('perbaikan.status') as $k=>$v )
                                            <option value="{{ $k }}" {{ val($dataForm, 'status_kerja')==$k ? 'selected' : '' }} >{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label>Tanggal Selesai</label>
                                        <input type="text" class="form-control tDate" name="tgl_selesai" maxlength="20" value="{{ val($dataForm, 'tgl_selesai') ? formatDate(val($dataForm, 'tgl_selesai'), 2) : '' }} " />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Permintaan</label><span class="char_count"></span>
                                <textarea name="permintaan" class="form-control">{!! val($dataForm, 'permintaan') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Keterangan</label><span class="char_count"></span>
                                <textarea name="keterangan" class="form-control">{!! val($dataForm, 'keterangan') !!}</textarea>
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
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table contextmenu directionality',
        'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
    ],
    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
    image_advtab: true,
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
    ] 
});
</script>
@endpush