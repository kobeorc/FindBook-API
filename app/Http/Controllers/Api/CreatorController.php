<?php

namespace App\Http\Controllers\Api;

use App\Models\Creator;

class CreatorController extends ApiController
{
    public function publisher()
    {
        $publishers = Creator::query()->whereType(Creator::TYPE_PUBLISHER)->get();

        return $this->jsonResponse($publishers);
    }
}
