<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"> {{ val($dataForm, 'form_title') }} </h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                
                <form method="POST" class="form-ajax" action="{{ BeUrl(config('pustakagambar.info.alias').'/save') }}" id="uploader_formData" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box-body">
                                <div class="text-center">
                                    <img  src="{{ val($dataForm, 'image') ? asset('media/'.val($dataForm, 'image')) : asset('/global/images/no-image.png') }}" id="uploader_imagePreview" style="width: 100%;"><br/><br/>
                                    <a href="#" class="btn btn-sm btn-flat btn-default" id="uploader_changeImage"><i class="fa fa-pencil"></i> {{ trans('pustakagambar::global.change_image') }} </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--div class="form-group has-feedback">
                                <input type="checkbox" name="status" {{ isset($dataForm['status']) ? (val($dataForm, 'status')=='1' ? 'checked' : '') : 'checked' }} /> {{ trans('global.status_active') }}
                            </div-->

                            <div class="form-group has-feedback">
                                <label>{{ trans('pustakagambar::global.copyright') }}</label><span class="char_count"></span>
                                <input type="text" class="form-control" name="copyright" placeholder="&copy;" maxlength="50" value="{{ val($dataForm, 'copyright') }}" />
                            </div>

                            <div class="form-group has-feedback">
                                <label>{{ trans('pustakagambar::global.keterangan') }}</label><span class="char_count"></span>
                                <input type="text" class="form-control" name="keterangan" maxlength="125" value="{{ val($dataForm, 'keterangan') }}" />
                            </div>

                            <div class="form-group has-feedback">
                                <label>{{ trans('pustakagambar::global.kategori') }}</label><span class="char_count"></span>
                                <input type="text" class="form-control" name="kategori" maxlength="50" value="{{ val($dataForm, 'kategori') }}" />
                            </div>

                            <button type="submit" class="btn btn-primary btn-flat">{{ val($dataForm, 'id') ? trans('global.act_edit') : trans('global.act_add') }}</button>
                            <a href="{{ BeUrl(config('pustakagambar.info.alias').'/edit/0') }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_cancel') }}</a>
                            
                        </div>
                    </div>
                    

                    <input type="hidden" name="id" value="{{ val($dataForm, 'id') }}" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="file" name="image" id="uploader_inputImage" style="height: 0; width: 0;"/>
                </form>
                
            </div><!-- /.box-body -->
        </div>
    </div>
</div>