<div id="imageSelectorLibrary">
    <div class="filter">
        <form method="GET" class="form-filter">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                <input type="text" class="form-control" name="filter-keterangan" placeholder="{{ trans('pustakagambar::global.cari') }}" value="{{ val($_GET, 'filter-keterangan') }}"/>
                </div>
                <div class="col-xs-7 col-md-3">
                <select class="form-control select2" name="filter-kategori">
                    <option value="all" {{val($_GET, 'filter-kategori')=='all' ? 'selected=1' : ''}} >{{ trans('global.all') }}</option>
                    @if (!empty($kategori))
                    @foreach($kategori as $k)
                    <option value="{{$k}}" {{val($_GET, 'filter-kategori')==$k ? 'selected=1' : ''}}>{{ $k }}</option>
                    @endforeach
                    @endif
                </select>
                </div>
                <div class="col-xs-5 col-md-2">
                <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> {{ trans('global.filter') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row-fluid" style="padding-top: 10px;">
        @if (!empty($images))
        @foreach( $images as $r )
            <div class="col-xs-6 col-md-3 item">
            <div class="item-inner">
                <div class="img select-image" title="{{ trans('pustakagambar::global.pilih_gambar') }}" data-path="{{ $r->image }}" data-description="{{ $r->keterangan }}" data-copyright="{{ $r->copyright }}" data-url="{{imgUrl($r->image)}}"  style="background:url({{imgUrl($r->image)}}) 50% 50% no-repeat; cursor: pointer;" data-toggle="modal" data-target="#modalPreview-{{$r->id}}"></div>
                <div class="text">
                    <p class="keterangan">{{ $r->keterangan }}{!! $r->copyright ? '<span class="nowrap"> '.$r->copyright.' </span>' : '' !!}</p>
                    <p class="kategori">{{ $r->kategori }}</p>
                </div>
                <div class="text-center" style="margin: 5px -6px -35px -6px;">
                <div class="button btn-group btn-block" role="group">
                    <button type="button" class="btn btn-sm btn-flat btn-default btn-select btn-block select-image" data-path="{{ $r->image }}" data-description="{{ $r->keterangan }}" data-url="{{imgUrl($r->image)}}" title="{{ trans('pustakagambar::global.pilih_gambar') }}"><i class="fa fa-check"></i> {{ trans('pustakagambar::global.pilih') }}</button>
                </div>
                </div>
            </div>
            </div>
        @endforeach
        @endif
    </div>
    <div class="clearfix"></div>
    <div class="text-center" style="background: #ddd;margin: -5px;margin-top: 5px;">{!! $images->appends(Input::except('page'))->render() !!}</div>
</div>
<style>
#imageSelectorLibrary {
    padding: 5px;
}
#imageSelectorLibrary .filter {
    background: #ddd;
    margin: -10px -5px;
    padding: 10px;
}
#imageSelectorLibrary .item{
  padding: 5px;
  margin-bottom: 30px;
}
#imageSelectorLibrary .item-inner{
  border: 1px solid #ddd;
  background: #fff;
}
#imageSelectorLibrary .item-inner .img {
  height: 150px;
}
#imageSelectorLibrary .item-inner .text {
  text-align: center;
  padding: 5px;
  margin-bottom: -20px;
}
#imageSelectorLibrary .item-inner .text .keterangan {
  font-style: italic;
  height: 30px;
  line-height: 15px;
  overflow: hidden;
}
#imageSelectorLibrary .item-inner .text .kategori {
  font-size: 11px;
  color: red;
  height: 13px;
  line-height: 13px;
  overflow-wrap: break-word;
  overflow: hidden;
}
#imageSelectorLibrary .item-inner .button {
  padding:5px;
}
#imageSelectorLibrary .pagination {
    margin: 10px 10px 5px;
}
#imageSelectorLibrary .pagination li a,
#imageSelectorLibrary .pagination li span {
    border-radius: 0;
}
</style>