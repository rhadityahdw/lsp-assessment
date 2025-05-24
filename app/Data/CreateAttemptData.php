<?php

namespace App\Data\Attempt;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation;
use Illuminate\Http\UploadedFile;

class CreateAttemptData extends Data
{
    public function __construct(
        #[Validation\Required, Validation\IntegerType, Validation\Exists('users', 'id')]
        public int $user_id,

        #[Validation\Required, Validation\IntegerType, Validation\Exists('schemes', 'id')]
        public int $scheme_id,

        #[Validation\Required, Validation\File(
            max: 2048,
            mimeTypes: ['image/jpeg', 'image/png', 'application/pdf']
        )]
        public UploadedFile $ktp,

        #[Validation\Required, Validation\File(
            max: 5120,
            mimeTypes: ['application/pdf', 'image/jpeg']
        )]
        public UploadedFile $ijazah,

        #[Validation\Required, Validation\Image(
            max: 1024,
            mimeTypes: ['image/jpeg', 'image/png']
        )]
        public UploadedFile $pas_foto,

        #[Validation\File(
            max: 5120,
            mimeTypes: ['application/pdf', 'image/jpeg']
        )]
        public ?UploadedFile $bukti_kerja = null,

        #[Validation\File(
            max: 10240,
            mimeTypes: ['application/pdf', 'image/jpeg', 'image/png']
        )]
        public ?UploadedFile $portofolio = null,

        #[Validation\ArrayType]
        #[Validation\Rule('answers.*.question_id', ['required', 'integer', 'exists:pre_assesments,id'])]
        #[Validation\Rule('answers.*.answer', ['required', 'in:yes,no'])]
        public array $answers = []
    ) {
    }

    public static function messages(): array
    {
        return [
            'ktp.required' => 'KTP wajib diupload',
            'ijazah.required' => 'Ijazah wajib diupload',
            'pas_foto.required' => 'Pas Foto wajib diupload',
            'ktp.mimeTypes' => 'Format KTP harus JPEG, PNG atau PDF',
            'ijazah.max' => 'Ukuran ijazah maksimal 5MB',
        ];
    }
}