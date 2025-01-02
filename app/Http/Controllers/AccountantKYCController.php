<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountantKYCRequest;
use App\Http\Resources\UserResource;
use App\Models\AccountantInformation;
use App\Models\AccountantProfile;
use App\Models\File;
use App\Models\User;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Accountant Profile
 *
 */
class AccountantKYCController extends Controller
{
    /**
     * @throws ApiError
     */
    public function __invoke(CreateAccountantKYCRequest $request): UserResource|JsonResponse
    {
        // TODO: prevent accountants that have been approved from accessing this endpoint

        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        $user->load('accountant_profile.kyc');

        /* @var AccountantProfile $profile */
        $profile = $user->accountant_profile;

        /* @var AccountantInformation $kyc */
        $kyc = $profile->kyc;

        DB::beginTransaction();

        // filter out all file inputs
        $kyc->update(collect($data)->filter(function ($value, $key) use ($request) {
            return !$request->hasFile($key);
        })->toArray());

        $profile_picture_upload = cloudinary()->upload($request->file('profile_picture')->getRealPath());
        $profile_picture_file = File::query()->create([
            'file_url' => $profile_picture_upload->getSecurePath(),
            'file_name' => $profile_picture_upload->getFileName(),
            'file_type' => $profile_picture_upload->getFileType(),
            'file_size' => $profile_picture_upload->getSize(),
        ]);
        $kyc->profile_picture()->associate($profile_picture_file);

        $proof_of_id_upload = cloudinary()->upload($request->file('proof_of_identity')->getRealPath());
        $proof_of_identity_file = File::query()->create([
            'file_url' => $proof_of_id_upload->getSecurePath(),
            'file_name' => $proof_of_id_upload->getFileName(),
            'file_type' => $proof_of_id_upload->getFileType(),
            'file_size' => $proof_of_id_upload->getSize(),
        ]);
        $kyc->proof_of_identity()->associate($proof_of_identity_file);

        $proof_of_address_upload = cloudinary()->upload($request->file('proof_of_address')->getRealPath());
        $proof_of_address_file = File::query()->create([
            'file_url' => $proof_of_address_upload->getSecurePath(),
            'file_name' => $proof_of_address_upload->getFileName(),
            'file_type' => $proof_of_address_upload->getFileType(),
            'file_size' => $proof_of_address_upload->getSize(),
        ]);
        $kyc->proof_of_address()->associate($proof_of_address_file);

        $professional_qualification_upload = cloudinary()->upload($request->file('professional_qualification_document')
            ->getRealPath());
        $professional_qualification_file = File::query()->create([
            'file_url' => $professional_qualification_upload->getSecurePath(),
            'file_name' => $professional_qualification_upload->getFileName(),
            'file_type' => $professional_qualification_upload->getFileType(),
            'file_size' => $professional_qualification_upload->getSize(),
        ]);
        $kyc->professional_qualification_document()->associate($professional_qualification_file);

        $proof_of_business_registration_upload = cloudinary()->upload($request->file('proof_of_business_registration')
            ->getRealPath());
        $proof_of_business_registration_file = File::query()->create([
            'file_url' => $proof_of_business_registration_upload->getSecurePath(),
            'file_name' => $proof_of_business_registration_upload->getFileName(),
            'file_type' => $proof_of_business_registration_upload->getFileType(),
            'file_size' => $proof_of_business_registration_upload->getSize(),
        ]);
        $kyc->proof_of_business_registration()->associate($proof_of_business_registration_file);

        DB::commit();

        // TODO: assign the role after approving accountant `$user->assignRole('accountant')`;

        return new UserResource($user);
    }
}
