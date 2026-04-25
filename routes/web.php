<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/appointment', function () {
    return view('pages.appointment');
})->name('appointment');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/department-details', function () {
    return view('pages.department-details');
})->name('department-details');

Route::get('/departments', function () {
    return view('pages.departments');
})->name('departments');

Route::get('/doctors', function () {
    return view('pages.doctors');
})->name('doctors');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/gallery', function () {
    return view('pages.gallery');
})->name('gallery');

Route::get('/landing', function () {
    return view('pages.landing');
})->name('landing');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/service-details', function () {
    return view('pages.service-details');
})->name('service-details');

Route::get('/services', function () {
    return view('pages.services');
})->name('services');

Route::get('/starter-page', function () {
    return view('pages.starter-page');
})->name('starter-page');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/testimonials', function () {
    return view('pages.testimonials');
})->name('testimonials');

Route::fallback(function () {
    return view('pages.404');
})->name('404');

Route::get('/terms', function () {
    return view('pages.terms');
});

Route::get('/testimonials', function () {
    return view('pages.testimonials');
});

Route::fallback(function () {
    return view('pages.404');
});

