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


class VoyagerEventsController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    protected $workingHoursRepository;

    public function __construct(WorkingHoursRepository $workingHoursRepository)
    {
        $this->workingHoursRepository = $workingHoursRepository;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
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