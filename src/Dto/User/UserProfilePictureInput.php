<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserProfilePictureInput
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\Image(
            maxSize: '2M',
            mimeTypes: ['image/jpeg', 'image/png', 'image/webp']
        )]
        public readonly ?UploadedFile $file = null,
    ){}
}
