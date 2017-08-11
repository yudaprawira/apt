<?php
namespace App\Http\Controllers\FE;

use App\Http\Requests;

use Lang, Route, Session, Request, Cookie, Redirect, Hash;

class HomeController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Parent Construct
    |-------------------------------------------------------------------------
    */
    function __construct() 
    {
        parent::__construct();
    }
    
    
    function index() 
    {
        return view($this->tmpl . 'homepage', $this->dataView);
    }
}