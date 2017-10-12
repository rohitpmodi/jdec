<?php

namespace App\Http\Controllers;

/**
 * Class LanguageController.
 *
 
 */
class LanguageController extends Controller
{
    public function setLocale($language)
    {
        LaravelLocalization::setLocale($language);

        return Redirect::route('dashboard');
    }
}
