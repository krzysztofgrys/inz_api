<?php
/**
 * Created by PhpStorm.
 * User: krzysztofgrys
 * Date: 10/16/17
 * Time: 6:53 PM
 */

namespace App\Users;

use \App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class  UsersController extends Controller
{

    public function index()
    {

        return 1;

    }

    public function show(Request $request, $users)
    {

    }



}