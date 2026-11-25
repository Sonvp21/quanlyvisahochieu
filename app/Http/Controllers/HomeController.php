<?php

namespace App\Http\Controllers;

use App\Support\Menu;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $h = (int) now()->format('G');

        return view('home', [
            'user'     => $user,
            'greeting' => $h < 11 ? 'Chào buổi sáng' : ($h < 14 ? 'Chào buổi trưa' : ($h < 18 ? 'Chào buổi chiều' : 'Chào buổi tối')),
            'groups'   => Menu::groups($user),
            'counters' => Menu::counters($user),
        ]);
    }
}
