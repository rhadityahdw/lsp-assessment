<?php

namespace App\Data;

use App\Enums\Gender;
use App\Models\Profile;
use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ProfileData extends Data
{
    public function __construct(
        #[FromAuthenticatedUserProperty(property: 'id')]
        public int $user_id,

        #[Required]
        #[StringType]
        #[Max(16)]
        public string $nik,

        #[Required]
        public Gender $gender,

        #[Required]
        public string $date_of_birth,

        #[Required]
        #[StringType]
        #[Max(255)]
        public string $place_of_birth,

        #[Required]
        #[StringType]
        public string $address,

        #[Required]
        #[StringType]
        #[Max(15)]
        public string $phone_number,

        #[Required]
        #[StringType]
        public string $education,

        #[Required]
        #[StringType]
        public string $job_title,

        #[Nullable]
        #[StringType]
        public ?string $company_name = null,
        
        #[Nullable]
        #[StringType]
        public ?string $company_address = null,
        
        #[Nullable]
        #[StringType]
        #[Max(15)]
        public ?string $company_phone = null,
        
        #[Nullable]
        #[Email]
        public ?string $company_email = null,
    ) {
        //
    }

    // Metode untuk membuat ProfileData dari model Profile
    public static function fromModel(Profile $profile): self
    {
        return new self(
            user_id: $profile->user_id,
            nik: $profile->nik,
            gender: $profile->gender,
            date_of_birth: $profile->date_of_birth,
            place_of_birth: $profile->place_of_birth,
            address: $profile->address,
            phone_number: $profile->phone_number,
            education: $profile->education,
            job_title: $profile->job_title,
            company_name: $profile->company_name,
            company_address: $profile->company_address,
            company_phone: $profile->company_phone,
            company_email: $profile->company_email,
        );
    }
}