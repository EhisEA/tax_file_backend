<?php

namespace App\Http\Controllers;

use App\Actions\UploadFileAction;
use App\Exceptions\FailedToUploadException;
use App\Http\Requests\CreateAccountantKYCRequest;
use App\Http\Resources\UserResource;
use App\Models\AccountantInformation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Accountant Profile
 */
class AccountantKYCController extends Controller
{
    /**
     * @throws FailedToUploadException
     */
    public function __invoke(CreateAccountantKYCRequest $request, UploadFileAction $uploadFileAction): UserResource|JsonResponse
    {
        // TODO: prevent accountants that have been approved from accessing this endpoint

        /* @var User $user */
        $user = $request->user();
        $data = $request->validated();

        /* @var AccountantInformation $kyc */
        $kyc = $user->profile->kyc()->sole();

        DB::beginTransaction();

        // filter out all file inputs
        $none_file_data = collect($data)->filter(function ($value, $key) use ($request) {
            return ! $request->hasFile($key);
        });

        $kyc->update($none_file_data->toArray());

        $kyc->profile_picture()->associate($uploadFileAction->execute($request->file('profile_picture')
            ->getRealPath()));

        $kyc->proof_of_identity()->associate($uploadFileAction->execute($request->file('proof_of_identity')
            ->getRealPath()));

        $kyc->proof_of_address()->associate($uploadFileAction->execute($request->file('proof_of_address')
            ->getRealPath()));

        $kyc->professional_qualification_document()
            ->associate($uploadFileAction->execute($request->file('professional_qualification_document')
                ->getRealPath()));

        $kyc->proof_of_business_registration()
            ->associate($uploadFileAction->execute($request->file('proof_of_business_registration')
                ->getRealPath()));

        $kyc->save();

        DB::commit();

        // TODO: assign the role after approving accountant `$user->assignRole('accountant')`;

        return new UserResource($user);
    }
}
