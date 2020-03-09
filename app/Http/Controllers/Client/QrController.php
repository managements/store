<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\QrRequest;
use App\Storage\QrStorage;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index(QrStorage $qrStorage)
    {
        return $qrStorage->get();
    }

    public function create()
    {
        return view('client.qr');
    }

    public function store(QrRequest $request,QrStorage $storage)
    {
        return $storage->create($request->number);
    }
}
