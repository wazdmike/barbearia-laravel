<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    private function checkAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerir os serviços.');
        }
    }

    public function index(): View
    {
        $this->checkAdmin();

        $services = Service::latest()->paginate(10);
        return view('services.index', compact('services'));
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $this->checkAdmin();

        Service::create($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Serviço registado com sucesso!');
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->checkAdmin();

        $service->update($request->validated());

        return redirect()->route('services.index')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->checkAdmin();

        if ($service->appointments()->exists()) {
            return redirect()->route('services.index')
                ->with('error', 'Não é possível remover um serviço que possui agendamentos associados.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Serviço removido com sucesso!');
    }
}