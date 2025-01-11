<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountantKYCRequest;
use App\Http\Resources\UserResource;
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

        $user->load('accountantProfile.kyc');
        $kyc = $user->accountantProfile->kyc;

        DB::beginTransaction();

        // filter out all file inputs
        $kyc->update(collect($data)->filter(function ($value, $key) use ($request) {
            return !$request->hasFile($key);
        })->toArray());

        $profile_picture_upload = cloudinary()->upload($request->file('profile_picture')->getRealPath());
        $kyc->profile_picture()->associate(File::query()->create([
            'file_url' => $profile_picture_upload->getSecurePath(),
            'file_name' => $profile_picture_upload->getFileName(),
            'file_type' => $profile_picture_upload->getFileType(),
            'file_size' => $profile_picture_upload->getSize(),
        ]));

        $proof_of_id_upload = cloudinary()->upload($request->file('proof_of_identity')->getRealPath());
        $kyc->proof_of_identity()->associate(File::query()->create([
            'file_url' => $proof_of_id_upload->getSecurePath(),
            'file_name' => $proof_of_id_upload->getFileName(),
            'file_type' => $proof_of_id_upload->getFileType(),
            'file_size' => $proof_of_id_upload->getSize(),
        ]));

        $proof_of_address_upload = cloudinary()->upload($request->file('proof_of_address')->getRealPath());
        $kyc->proof_of_address()->associate(File::query()->create([
            'file_url' => $proof_of_address_upload->getSecurePath(),
            'file_name' => $proof_of_address_upload->getFileName(),
            'file_type' => $proof_of_address_upload->getFileType(),
            'file_size' => $proof_of_address_upload->getSize(),
        ]));

        $professional_qualification_upload = cloudinary()->upload($request->file('professional_qualification_document')
            ->getRealPath());
        $kyc->professional_qualification_document()->associate(File::query()->create([
            'file_url' => $professional_qualification_upload->getSecurePath(),
            'file_name' => $professional_qualification_upload->getFileName(),
            'file_type' => $professional_qualification_upload->getFileType(),
            'file_size' => $professional_qualification_upload->getSize(),
        ]));

        $proof_of_business_registration_upload = cloudinary()->upload($request->file('proof_of_business_registration')
            ->getRealPath());
        $kyc->proof_of_business_registration()->associate(File::query()->create([
            'file_url' => $proof_of_business_registration_upload->getSecurePath(),
            'file_name' => $proof_of_business_registration_upload->getFileName(),
            'file_type' => $proof_of_business_registration_upload->getFileType(),
            'file_size' => $proof_of_business_registration_upload->getSize(),
        ]));

        DB::commit();

        // TODO: assign the role after approving accountant `$user->assignRole('accountant')`;

        return new UserResource($user);
    }
}
