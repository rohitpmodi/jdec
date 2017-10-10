<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Repositories\AlertRepository;
use Flash;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AlertController extends AppBaseController
{
    /** @var AlertRepository */
    private $alertRepository;

    public function __construct(AlertRepository $alertRepo)
    {
        $this->alertRepository = $alertRepo;
    }

    /**
     * Display a listing of the Alert.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->alertRepository->pushCriteria(new RequestCriteria($request));
        $alerts = $this->alertRepository->all();

        return view('backend.alerts.index')
            ->with('alerts', $alerts);
    }

    /**
     * Show the form for creating a new Alert.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.alerts.create');
    }

    /**
     * Store a newly created Alert in storage.
     *
     * @param CreateAlertRequest $request
     *
     * @return Response
     */
    public function store(CreateAlertRequest $request)
    {
        $input = $request->all();

        $alert = $this->alertRepository->create($input);

        Flash::success('Alert saved successfully.');

        return redirect(route('admin.alerts.index'));
    }

    /**
     * Display the specified Alert.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $alert = $this->alertRepository->findWithoutFail($id);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('admin.alerts.index'));
        }

        return view('backend.alerts.show')->with('alert', $alert);
    }

    /**
     * Show the form for editing the specified Alert.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $alert = $this->alertRepository->findWithoutFail($id);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('admin.alerts.index'));
        }

        return view('backend.alerts.edit')->with('alert', $alert);
    }

    /**
     * Update the specified Alert in storage.
     *
     * @param int                $id
     * @param UpdateAlertRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAlertRequest $request)
    {
        $alert = $this->alertRepository->findWithoutFail($id);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('admin.alerts.index'));
        }

        $alert = $this->alertRepository->update($request->all(), $id);

        Flash::success('Alert updated successfully.');

        return redirect(route('admin.alerts.index'));
    }

    /**
     * Remove the specified Alert from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $alert = $this->alertRepository->findWithoutFail($id);

        if (empty($alert)) {
            Flash::error('Alert not found');

            return redirect(route('admin.alerts.index'));
        }

        $this->alertRepository->delete($id);

        Flash::success('Alert deleted successfully.');

        return redirect(route('admin.alerts.index'));
    }
}
