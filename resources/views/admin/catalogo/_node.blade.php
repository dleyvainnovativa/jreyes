{{-- Una fila del árbol + sus hijos (recursivo). Espera $node y $depth. --}}
@php($depth = $depth ?? 0)

<div class="jr-tree__row {{ $node->activo ? '' : 'is-hidden' }}" style="--depth: {{ $depth }}">
    <div class="jr-tree__main">
        <span class="jr-tree__name">{{ $node->nombre }}</span>
        <span class="jr-tree__kind">{{ $node->kind }}</span>
        @unless ($node->activo)
            <span class="jr-tree__badge jr-tree__badge--off">Oculto</span>
        @endunless
        @if ($node->es_extra)
            <span class="jr-tree__badge jr-tree__badge--extra">Extra</span>
        @endif
    </div>

    <div class="jr-tree__side">
        <span class="jr-tree__price">
            @if ((float) $node->precio > 0)
                ${{ number_format($node->precio, 2) }}
            @else
                <span class="jr-text-dim">En tienda</span>
            @endif
        </span>
        <a href="{{ route('admin.catalogo.edit', $node) }}" class="btn btn-sm btn-jr-outline">
            <i class="fa-solid fa-pen" aria-hidden="true"></i>
            <span class="d-none d-sm-inline">Editar</span>
        </a>
    </div>
</div>

@foreach ($node->children as $hijo)
    @include('admin.catalogo._node', ['node' => $hijo, 'depth' => $depth + 1])
@endforeach
