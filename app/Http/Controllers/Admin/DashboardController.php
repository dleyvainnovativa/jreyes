<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogNode;

/**
 * Inicio del panel de administración.
 * Resumen rápido del catálogo; el editor del árbol llega en el siguiente paso.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'nodos'        => CatalogNode::count(),
            'tratamientos' => CatalogNode::where('kind', 'tratamiento')->count(),
            'sinPrecio'    => CatalogNode::where('kind', 'tratamiento')->where('precio', 0)->count(),
            'raices'       => CatalogNode::whereNull('parent_id')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
