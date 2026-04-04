<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\CommercialProduct;
use App\Models\Formation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommercialProductController extends Controller
{
    public function index()
    {
        $products = CommercialProduct::with('formation')
            ->latest()
            ->paginate(20)
            ->through(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'reference'   => $p->reference,
                'unit_price'  => $p->unit_price,
                'unit'        => $p->unit,
                'tax_rate'    => $p->tax_rate,
                'is_active'   => $p->is_active,
                'formation'   => $p->formation?->only(['id', 'title']),
            ]);

        return Inertia::render('Commercial/Products/Index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'formation_id' => ['nullable', 'exists:formations,id'],
            'name'         => ['required', 'string', 'max:255'],
            'reference'    => ['nullable', 'string', 'max:100', 'unique:commercial_products,reference'],
            'description'  => ['nullable', 'string'],
            'unit_price'   => ['required', 'numeric', 'min:0'],
            'unit'         => ['required', 'string', 'max:50'],
            'tax_rate'     => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        CommercialProduct::create($data);
        return back()->with('success', 'Produit créé.');
    }

    public function update(Request $request, CommercialProduct $product)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'unit'       => ['required', 'string', 'max:50'],
            'tax_rate'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_active'  => ['boolean'],
        ]);

        $product->update($data);
        return back()->with('success', 'Produit mis à jour.');
    }

    public function destroy(CommercialProduct $product)
    {
        $product->delete();
        return back()->with('success', 'Produit supprimé.');
    }

    /**
     * Synchronise automatiquement les formations publiées comme produits.
     */
    public function syncFromFormations()
    {
        $formations = Formation::published()->get();
        $created = 0;

        foreach ($formations as $f) {
            if (! CommercialProduct::where('formation_id', $f->id)->exists()) {
                CommercialProduct::create([
                    'formation_id' => $f->id,
                    'name'         => $f->title,
                    'unit_price'   => $f->current_price ?? 0,
                    'unit'         => 'formation',
                    'tax_rate'     => 0,
                    'is_active'    => true,
                ]);
                $created++;
            }
        }

        return back()->with('success', "{$created} formation(s) synchronisée(s) comme produits.");
    }
}