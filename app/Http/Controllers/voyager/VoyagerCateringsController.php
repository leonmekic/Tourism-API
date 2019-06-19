<?php

namespace App\Http\Controllers\Voyager;

use App\Contracts\Model;
use App\Models\User;
use App\Repositories\GeneralInfoRepository;
use App\Repositories\WorkingHoursRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;


class VoyagerCateringsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    protected $generalInfoRepository;
    protected $workingHoursRepository;

    public function __construct(GeneralInfoRepository $generalInfoRepository , WorkingHoursRepository $workingHoursRepository)
    {
        $this->generalInfoRepository = $generalInfoRepository;
        $this->workingHoursRepository = $workingHoursRepository;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'app_id'       => 'required|integer',
            'address'      => 'string',
            'phone_number' => 'string',
            'email'        => 'string',
            'day'          => 'string',
            'opens_at'     => Rule::requiredIf($request->input('day')),
            'closes_at'    => Rule::requiredIf($request->input('day'))
        ]);

        $slug = $this->getSlug($request);


        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        if (auth()->id() != User::SuperAdminId) {
            $request['app_id'] = auth()->user()->app_id;
        }
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        if ($request->address) {
            $payload = [];
            $payload['address'] = $request->input('address');
            $payload['phone_number'] = $request->input('phone_number');
            $payload['email'] = $request->input('email');

            $this->generalInfoRepository->createGeneralInfo($data, $payload);
        }

        if ($request->day) {
            $payLoad = [];
            $payLoad['day'] = $request->input('day');
            $payLoad['opens_at'] = $request->input('opens_at');
            $payLoad['closes_at'] = $request->input('closes_at');

            $this->workingHoursRepository->createWorkingHours($data, $payLoad);
        }

        event(new BreadDataAdded($dataType, $data));

        return redirect()->route("voyager.{$dataType->slug}.index")->with(
            [
                'message' => __(
                        'voyager::generic.successfully_added_new'
                    ) . " {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]
        );
    }
}