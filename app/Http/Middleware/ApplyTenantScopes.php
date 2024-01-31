<?php

namespace App\Http\Middleware;

use App\Models\Pet;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $clinic = Filament::getTenant();

        Pet::addGlobalScope(
            fn (Builder $query) => $query->whereHas('clinics', fn (Builder $query) => $query->where('clinics.id', $clinic->id))
        );

        return $next($request);
    }
}
