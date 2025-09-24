<?php

namespace App\Services\PictureService;

interface PictureServiceInterface {
    public function save(string $rawContent): string;
    public function destroy(string $fileName): void;
}
