<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(): View
    {
        $this->ensureAdmin();

        $services = Service::latest()->paginate(10);
        return view('services.index', compact('services'));
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $this->ensureAdmin();

        Service::create($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Serviço registado com sucesso!');
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->ensureAdmin();

        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->ensureAdmin();

        if ($service->appointments()->exists()) {
            return redirect()->route('services.index')
                ->with('error', 'Não é possível remover um serviço que possui agendamentos associados.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Serviço removido com sucesso!');
    }
}
