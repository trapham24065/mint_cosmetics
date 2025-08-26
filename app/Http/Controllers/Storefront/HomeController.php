<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/25/2025
 * @time 3:27 PM
 */

namespace App\Http\Controllers\Storefront;

class HomeController
{

    public function index()
    {
        return view('storefront.home');
    }

}
