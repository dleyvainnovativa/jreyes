<footer class="jr-footer jr-section-sm mt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <img src="{{ asset('img/logo-jreyes.png') }}" alt="JReyes Ópticos" class="jr-footer__logo mb-3">
                <p class="mb-3" style="max-width:32ch">Mejora lo que ves. Lentes graduados, progresivos de alta gama y lentes de contacto con la mejor asesoría.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-jr-outline btn-sm" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-jr-outline btn-sm" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="btn btn-jr-outline btn-sm" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h5 class="mb-3">Catálogo</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('lentes') }}">Lentes graduados</a></li>
                    <li><a href="{{ route('marcas') }}">Varilux y Crizal</a></li>
                    <li><a href="{{ route('lentes-contacto') }}">Lentes de contacto</a></li>
                    <li><a href="{{ route('paquetes') }}">Paquetes</a></li>
                    <li><a href="{{ route('empresa') }}">Empresa</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-3">
                <h5 class="mb-3">Contacto</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><i class="fa-solid fa-location-dot me-2 jr-text-gold"></i> Veracruz, México</li>
                    <li><i class="fa-solid fa-phone me-2 jr-text-gold"></i> (229) 000 0000</li>
                    <li><i class="fa-solid fa-envelope me-2 jr-text-gold"></i> hola@jreyesopticos.com</li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h5 class="mb-3">Horario</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li>Lunes a viernes: 9:00 – 19:00</li>
                    <li>Sábado: 9:00 – 15:00</li>
                    <li>Domingo: cerrado</li>
                </ul>
                <a href="{{ route('contacto') }}" class="btn btn-jr-gold btn-sm mt-2">Agenda tu examen</a>
            </div>
        </div>

        <hr class="jr-divider my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
            <span>© {{ date('Y') }} JReyes Ópticos. Todos los derechos reservados.</span>
            <span class="jr-text-muted">Precios en pesos mexicanos (MXN). Sujetos a cambio sin previo aviso.</span>
        </div>
    </div>
</footer>
