{{-- Tabla comparativa de tratamientos (Crizal). Espera $comparativa. --}}
<div class="table-responsive">
    <table class="jr-compare">
        <thead>
            <tr>
                <th scope="col" style="text-align:left">Tratamiento</th>
                <th scope="col">Protección UV</th>
                <th scope="col">Luz azul</th>
                <th scope="col">Reflejos</th>
                <th scope="col">Rayas</th>
                <th scope="col">Manchas</th>
                <th scope="col">Agua</th>
                <th scope="col">Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comparativa as $t)
            <tr>
                <th scope="row">
                    {{ $t->nombre }}
                    @if ($t->premium)<i class="fa-solid fa-star jr-text-gold ms-1" title="Premium" style="font-size:.7rem"></i>@endif
                </th>
                @foreach ($t->puntuaciones() as $valor)
                <td>
                    <span class="jr-score" role="img" aria-label="{{ $valor }} de 3">
                        @for ($i = 1; $i <= 3; $i++)
                            <span class="{{ $i <= $valor ? 'on' . ($t->premium ? ' gold' : '') : '' }}"></span>
                        @endfor
                    </span>
                </td>
                @endforeach
                <td class="is-price">${{ number_format($t->precio, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="jr-text-muted small mt-3 mb-0">Escala de 0 a 3 según el nivel de protección de cada tratamiento. Precios de referencia en MXN.</p>
