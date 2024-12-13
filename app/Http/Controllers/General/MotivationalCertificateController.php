<?php


namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\MotivationalCertificateRequest;
use App\Interfaces\MotivationalCertificateRepositoryInterface;;
use Illuminate\Http\Request;

class MotivationalCertificateController extends Controller
{
    protected $motivationalCertificateRepository;
    public function __construct(MotivationalCertificateRepositoryInterface $motivationalCertificateRepository)
    {
        $this->motivationalCertificateRepository = $motivationalCertificateRepository;
        $this->middleware('permission:show motivational certificate')->only(['index','show']);
        $this->middleware('permission:delete motivational certificate')->only('destroy');
        $this->middleware('permission:add motivational certificate')->only(['create','store']);
        $this->middleware('permission:export motivational certificate')->only('export');

    }

    public function index(Request $request)
    {
        return $this->motivationalCertificateRepository->index($request);

    }

    public function create()
    {
        return $this->motivationalCertificateRepository->create();

    }

    public function store(MotivationalCertificateRequest $request)
    {
        return $this->motivationalCertificateRepository->store($request);

    }

    public function show($id)
    {
        return $this->motivationalCertificateRepository->show($id);
    }

    public function destroy(Request $request)
    {
        return $this->motivationalCertificateRepository->destroy($request);

    }

    public function export(Request $request)
    {
        return $this->motivationalCertificateRepository->export($request);
    }

}
