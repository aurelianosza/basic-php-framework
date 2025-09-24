<?php

namespace App\Services\PictureService;

use DateTime;

class PictureUploadService implements PictureServiceInterface {
    
    private string $globalPathPicture;

    public function __construct(private string $picturePath)
    {
        $this->globalPathPicture = __DIR__. "/../../pictures";
    }

    public function save(string $rawContent): string
    {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $rawContent));

        $fileName = $this->getFileName();

        file_put_contents($fileName, $data);

        return str_replace($this->globalPathPicture, "", $fileName);
    }

    private function getFileName(): string
    {
        return implode("/", [
            $this->getFullPathName(),
            $this->getHashedFileName()
        ]);
    }

    private function getFullPathName(): string
    {
        $pathName = implode("/", [
            $this->globalPathPicture,
            $this->picturePath
        ]);

        if (!is_dir($pathName)) {
            mkdir($pathName);
        }

        return $pathName;
    }

    private function getHashedFileName(): string
    {
        $timestamp = (new Datetime())->getTimestamp();

        return md5($timestamp) . ".png";
    }

    public function destroy(string $fileName): void
    {
        $globalPathPicture = implode("/", [
            $this->globalPathPicture,
            $fileName
        ]);

        if (!file_exists($globalPathPicture)) {
            return;
        }

        unlink($globalPathPicture);
    }
}
