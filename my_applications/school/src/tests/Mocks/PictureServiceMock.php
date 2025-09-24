<?php

namespace tests\Mocks;

use App\Services\PictureService\PictureServiceInterface;

class PictureServiceMock implements PictureServiceInterface {

    public function save(string $rawContent): string
    {
        return "/mocking/". $rawContent;
    }

    public function destroy(string $fileName): void
    {
        return;
    }
}
