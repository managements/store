<?php

namespace App\Storage;

use App\Partner;
use App\Qr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class QrStorage
{
    // ajouté une liste des QR Code <! valide !>
    // attaché un qt Code a un client < lecteur de qrCode> (mobile)
    // téléchargé une liste des QR Code non attaché <! valide !>
    // telecharger un seul QR Code attaché <! valide !>
    private $img = [];

    public function create(int $nbr)
    {

        for ($i = 0; $i < $nbr+1; $i++){
            $img = $this->add();
            $this->img[] = $img->img;
        }

        return $this->download($this->img);
    }

    public function add()
    {
        $code = $this->getCode();

        QrCode::format('png')->size(250)->generate($code, public_path('qr/' . $code . '.png'));
        $img = "qr/$code.png";
        $qr = Qr::create([
            'img' => $img, 'code' => $code,
        ]);
        return $qr;
    }

    public function download(array $imgs)
    {
        // telecharger tous les QR Code non attaché
        $this->deleteFile();
        $zip = new ZipArchive;
        $fileName = 'qrCode.zip';

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            $files = File::files(public_path('qr'));
            foreach ($files as $key => $value) {
                if (in_array('qr/'.basename($value),$imgs)) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
            }
            $zip->close();
        }
        return response()->download(public_path($fileName));
    }

    private function deleteFile()
    {
        if (Storage::exists(public_path() . 'qrCode.zip')) {
            Storage::delete('qrCode.zip');
        }
    }

    private function singleDownload(string $img)
    {
        // telechargé un seul QR Code
        $this->deleteFile();
        $zip = new ZipArchive;
        $fileName = 'qrCode.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            $files = File::files(public_path('qr'));
            foreach ($files as $key => $value) {
                if ('qr/' . basename($value) === $img) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
            }
            $zip->close();
        }
        return response()->download(public_path($fileName));
    }

    private function getCode()
    {
        $code = Str::random(10);
        if ($this->issetCode($code)) {
            return $code;
        }
        return $this->getCode();
    }

    public function issetCode($code)
    {
        if (!Qr::where('code', $code)->first()) {
            return true;
        }
        return false;
    }

    public function attach(Partner $partner, Qr $qr)
    {
        return $qr->update([
            'partner_id' => $partner->id
        ]);
    }
}