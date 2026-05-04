<?php

namespace App\Http\Controllers;

class PageController extends Controller
{

    public function home()
    {
        return view('pages.home');
    }

    public function news()
    {
        return view('pages.news');
    }

    public function complaint()
    {
        return view('pages.complaint');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function about()
    {
        return view('pages.about');
    }
}
