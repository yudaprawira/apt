@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <div class="row" id="page-imglib">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data {{ config('pustakagambar.info.name') }} </h3>
                  <div class="link-table-pane">
                    <a href="{{ BeUrl(config('pustakagambar.info.alias')) }}" class="{{ $isTrash ? '' : 'active'}}">{{ trans('global.all') }} <span>({{$countAll}})</span></a> | 
                    <a href="{{ BeUrl(config('pustakagambar.info.alias').'/trash') }}" class="{{ $isTrash ? 'active' : ''}}">{{ trans('global.trash') }} <span>({{$countTrash}})</span></a>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <form method="GET">
                    <div class="row">
                      <div class="col-md-6">
                        <input type="text" class="form-control" name="filter-keterangan" placeholder="{{ trans('pustakagambar::global.cari') }}" value="{{ val($_GET, 'filter-keterangan') }}"/>
                      </div>
                      <div class="col-md-2">
                        <select class="form-control select2" name="filter-kategori">
                          <option value="all" {{val($_GET, 'filter-kategori')=='all' ? 'selected=1' : ''}} >{{ trans('global.all') }}</option>
                          @if (!empty($kategori))
                          @foreach($kategori as $k)
                          <option value="{{$k}}" {{val($_GET, 'filter-kategori')==$k ? 'selected=1' : ''}}>{{ $k }}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> {{ trans('global.filter') }}</button>
                      </div>
                      <div class="col-md-2 no-padding">
                        <a href="#" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modalInput-Image" data-backdrop="static"><i class="fa fa-plus"></i> {{ trans('global.add') }}</a>
                      </div>
                    </div>
                  </form>
                </div><!-- /.box-body -->
              </div>
        </div>
        
          
        <!-- Modal -->
        <div class="modal fade modalInput" id="modalInput-Image" tabindex="-1" role="dialog" aria-labelledby="modalInput-Image-Label" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content box">
              <div class="modal-header" style="border: none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                {!! $form !!}
              </div>
              <div class="overlay delete-loding" style="display: none;">
                  <i class="fa fa-refresh fa-spin"></i>
              </div>
            </div>
          </div>
        </div>
        
    </div>

    <div class="box" style="background: none;box-shadow: none;border: none;">
        <div class="box-body no-padding">
            <div class="row-fluid" style="margin-left: -5px;margin-right: -5px;">
            @if (!empty($rowImages))
            @foreach( $rowImages as $rImg )
              <div class="col-md-3 item">
                <div class="item-inner">
                  <div class="img" style="background:url({{imgUrl($rImg->image)}}) 50% 50% no-repeat; cursor: pointer;" data-toggle="modal" data-target="#modalPreview-{{$rImg->id}}"></div>
                  <div class="text">
                    <p class="keterangan">{{ $rImg->keterangan }} {!! $rImg->copyright ? '<span class="nowrap"> '.$rImg->copyright.' </span>' : '' !!}</p>
                    <p class="kategori">{{ $rImg->kategori }}</p>
                  </div>
                  <div class="text-center" style="margin: 5px -8px -35px -6px;">
                    <div class="button btn-group btn-block" role="group">
                      @if ($isTrash)
                      <button type="button" class="btn btn-sm btn-flat btn-default btn-copy" data-text="{{imgUrl($rImg->image)}}" style="width: 61%;" title="Salin Alamat URL"><i class="fa fa-copy"></i> Salin URL</button>
                      <a href="{{ BeUrl(config('pustakagambar.info.alias').'/edit/'.$rImg->id) }}" class="btn btn-sm btn-flat btn-primary btn-edit" style="width: 13%;" title="Edit"><i class="fa fa-pencil"></i></a>
                      <button type="button" data-toggle="modal" data-target="#modalConfirmationRestore-{{$rImg->id}}" data-backdrop="static" class="btn btn-sm btn-flat btn-info" style="width: 13%;" title="Restore"><i class="fa fa-share-square-o"></i></button>
                      <button type="button" data-toggle="modal" data-target="#modalConfirmation-{{$rImg->id}}" data-backdrop="static" class="btn btn-sm btn-flat btn-danger" style="width: 13%;" title="Hapus"><i class="fa fa-trash"></i></button>
                      @else
                      <button type="button" class="btn btn-sm btn-flat btn-default btn-copy" data-text="{{imgUrl($rImg->image)}}" style="width: 74%;" title="Salin Alamat URL"><i class="fa fa-copy"></i> Salin URL</button>
                      <a href="{{ BeUrl(config('pustakagambar.info.alias').'/edit/'.$rImg->id) }}" class="btn btn-sm btn-flat btn-primary btn-edit" style="width: 13%;" title="Edit"><i class="fa fa-pencil"></i></a>
                      <button type="button" data-toggle="modal" data-target="#modalConfirmation-{{$rImg->id}}" data-backdrop="static" class="btn btn-sm btn-flat btn-danger" style="width: 13%;" title="Hapus"><i class="fa fa-trash"></i></button>
                      @endif
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal -->
              <div class="modal fade modalConfirmation" id="modalConfirmation-{{$rImg->id}}" tabindex="-1" role="dialog" aria-labelledby="modalConfirmation-{{$rImg->id}}-Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content box">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="modalConfirmation-{{$rImg->id}}-Label">{{ trans('global.confirm_del') }}</h4>
                    </div>
                    <div class="modal-body">
                      @if ($isTrash)
                        {!! trans('global.confirm_text_permanent', ['name'=>$rImg->keterangan]) !!}
                      @else
                        {!! trans('global.confirm_text', ['name'=>$rImg->keterangan]) !!}
                      @endif
                      <div class="text-center">
                        <img src="{{imgUrl($rImg->image)}}" style="max-width: 200px;"/>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('global.act_close') }}</button>
                      <a href="{{ BeUrl(config('pustakagambar.info.alias').'/delete/'.$rImg->id.($isTrash ? '?permanent=1' : '')) }}" class="btn btn-danger btn-flat btn-delete">{{ trans('global.delete') }}</a>
                    </div>
                    
                    <div class="overlay delete-loding" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal Preview -->
              <div class="modal fade modalPreview" id="modalPreview-{{$rImg->id}}" tabindex="-1" role="dialog" aria-labelledby="modalPreview-{{$rImg->id}}-Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content box">
                    <div class="modal-header" style="border: none;">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="text-center">
                            <img src="{{imgUrl($rImg->image)}}" style="width: 100%;"/>
                          </div>
                        </div>
                        <div class="col-md-4 no-padding">
                          <table class="table">
                            <tr><th>{{ trans('pustakagambar::global.copyright') }}</th><td>{{$rImg->copyright}}</td></tr>
                            <tr><th>{{ trans('pustakagambar::global.kategori') }}</th><td>{{$rImg->kategori}}</td></tr>
                            <tr><th>{{ trans('pustakagambar::global.keterangan') }}</th><td>{{$rImg->keterangan}}</td></tr>
                          </table>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
              
              @if ($isTrash)
              <!-- Modal Restore  -->
              <div class="modal fade modalConfirmation" id="modalConfirmationRestore-{{$rImg->id}}" tabindex="-1" role="dialog" aria-labelledby="modalConfirmationRestore-{{$rImg->id}}-Label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content box">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="modalConfirmationRestore-{{$rImg->id}}-Label">{{ trans('global.confirm_res') }}</h4>
                    </div>
                    <div class="modal-body">
                      
                      {!! trans('global.confirm_rest', ['name'=>$rImg->keterangan]) !!}

                      <div class="text-center">
                        <img src="{{imgUrl($rImg->image)}}" style="max-width: 200px;"/>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('global.act_close') }}</button>
                      <a href="{{ BeUrl(config('pustakagambar.info.alias').'/restore/'.$rImg->id) }}" class="btn btn-info btn-flat btn-delete">{{ trans('global.restore') }}</a>
                    </div>
                    
                    <div class="overlay delete-loding" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              
            @endforeach
            @endif
            </div>
            <div class="clearfix"></div>
            <div class="text-center">{!! $rowImages->appends(Input::except('page'))->render() !!}</div>
          </div>
        </div><!-- /.box-body -->
    </div>
@endsection
@push('style')
<style>
.btn-add{
  margin-right: 5px;
}
#list-table td:nth-child(3),
#list-table td:nth-child(4),
#list-table td:nth-child(5){
  white-space: nowrap;
  text-align: center;
}
.item{
  padding: 5px;
  margin-bottom: 30px;
}
.item-inner{
  border: 1px solid #ddd;
  background: #fff;
}
.item-inner .img {
  height: 250px;
}
.item-inner .text {
  text-align: center;
  padding: 5px;
  margin-bottom: -20px;
}
.item-inner .text .keterangan {
  font-style: italic;
  height: 30px;
  line-height: 15px;
  overflow: hidden;
}
.item-inner .text .kategori {
  font-size: 11px;
  color: red;
  height: 13px;
  line-height: 13px;
  overflow-wrap: break-word;
  overflow: hidden;
}
.item-inner .button {
  padding:5px;
}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function(){
  $('#modalInput-Image, .modalConfirmation').on('hidden.bs.modal', function(){
    if ( $(this).attr('isReload') )
    {
      window.location.reload();
      //window.location.href='{{ BeUrl(config('pustakagambar.info.alias')) }}';
    }
  });
  $(document).on('click', '.btn-copy', function(){
    copyToClipboard($(this).data('text'));
    var msg = '<ul id="text-message-notif" style="display: none;"><li data-type="success" data-align="center" data-width="auto" data-close="false" data-name="">{!! trans('pustakagambar::global.copied') !!}</li></ul>';
    initNotif(msg);
    return false;
  });
});

function copyToClipboard(text) 
{
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(text).select();
  document.execCommand("copy");
  $temp.remove();
}
$(document).on('click', '.btn-edit', function(){
  $('#modalInput-Image').modal('show');
  return false;
});

</script>
@endpush