<?php

use Modules\PustakaGambar\Models\PustakaGambar;

function imageSelector()
{
    $fullPath = Route::current()->uri();
    $backPath = config('app.backend').'/';
    $curnPath = $fullPath;

    //if ( substr($fullPath, 0, strlen($backPath)) == $backPath  )
        $curnPath = explode('/', $fullPath)[0];

    return view('global.selector', ['path'=>$curnPath]);
}

/**
* @params $params = array(url, copyright, description, category) 
**/
function imageImport($params, $size=['640xauto', '200xauto'], $aspectRatio=true)
{
    $mainDir = "pustakagambar";

    $mainPath = 'media/';

    $path = $mainDir."/".date("Y/m/d/");

    //check exists
    if ( $row = PustakaGambar::where('source', val($params, 'url'))->first() )
    {
        return $row->image;
    }
    
    //mkdir
    if ( !file_exists( public_path($mainPath.$path) ) ) mkdir( public_path($mainPath.$path), 0777, true );

    foreach ( $size as $s )
    {
        $arrSize = explode('x', $s);

        if ( isset($arrSize[1]) )
        {
            
            $pathInfo = pathinfo(val($params, 'url'));

            $urlName = str_slug(val($pathInfo, 'filename'));

            $filename  = $urlName.'-'.$s.'.'.val($pathInfo, 'extension');

            $realPath = public_path($mainPath . $path . $filename);

            $imageSource = val($params, 'url');

            if ($r = Image::make($imageSource)->resize($arrSize[0], $arrSize[1], function ($constraint) use($aspectRatio) {
                if ($aspectRatio) $constraint->aspectRatio();
            })->interlace(true)->save($realPath))
            {
                $ret[$s] = $path.$r->basename;
            }
        }
    }

    //insert
    PustakaGambar::insert([
        'copyright' => val($params, 'copyright'),
        'keterangan' => val($params, 'description'),
        'kategori' => val($params, 'category'),
        'url' => $urlName,
        'image' => val($ret, val($size, 0)),
        'source' => val($params, 'url'),
    ]);

    return val($ret, val($size, 0));
}

?>