</main>

<footer class="footer-premium">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h3 class="footer-title">SPORTSHOP</h3>
                <p class="mt-3">Sua loja de artigos esportivos com as melhores marcas e preços.</p>
                <div class="mt-3">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            
            <div class="col-md-2">
                <h3 class="footer-title">Links Úteis</h3>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-text-primary">Sobre Nós</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-text-primary">Contato</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-text-primary">Trocas e Devoluções</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-muted hover-text-primary">Política de Privacidade</a></li>
                </ul>
            </div>
            
            <div class="col-md-3">
                <h3 class="footer-title">Contato</h3>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> (11) 99999-9999</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> contato@sportshop.com</li>
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> São Paulo, SP</li>
                </ul>
            </div>
            
            <div class="col-md-3">
                <h3 class="footer-title">Horário de Funcionamento</h3>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2">Segunda a Sexta: 9h às 18h</li>
                    <li class="mb-2">Sábado: 9h às 13h</li>
                    <li class="mb-2">Domingo: Fechado</li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
        
        <div class="text-center">
            <p class="mb-0">&copy; 2024 SportShop. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

    const searchInput = document.querySelector('.search-input');
    const searchSuggestions = document.querySelector('#search-suggestions');

    if (searchInput && searchSuggestions) {
        let searchTimer;
        let activeRequest;

        const hideSuggestions = () => {
            searchSuggestions.classList.remove('is-visible');
            searchInput.setAttribute('aria-expanded', 'false');
        };

        const showStatus = (message) => {
            searchSuggestions.replaceChildren();
            const status = document.createElement('div');
            status.className = 'search-suggestions-status';
            status.textContent = message;
            searchSuggestions.append(status);
            searchSuggestions.classList.add('is-visible');
            searchInput.setAttribute('aria-expanded', 'true');
        };

        searchInput.addEventListener('input', () => {
            const termo = searchInput.value.trim();
            window.clearTimeout(searchTimer);
            if (activeRequest) activeRequest.abort();

            if (termo.length < 2) {
                hideSuggestions();
                return;
            }

            searchTimer = window.setTimeout(async () => {
                activeRequest = new AbortController();
                showStatus('Buscando produtos...');

                try {
                    const response = await fetch(`busca_sugestoes.php?q=${encodeURIComponent(termo)}`, {
                        signal: activeRequest.signal
                    });
                    if (!response.ok) throw new Error('Falha na busca');
                    const produtos = await response.json();

                    if (searchInput.value.trim() !== termo) return;
                    searchSuggestions.replaceChildren();

                    if (produtos.length === 0) {
                        showStatus('Nenhum produto encontrado.');
                        return;
                    }

                    produtos.forEach((produto) => {
                        const link = document.createElement('a');
                        link.className = 'search-suggestion';
                        link.href = `produto.php?id=${encodeURIComponent(produto.id)}`;
                        link.setAttribute('role', 'option');

                        const image = document.createElement('img');
                        image.src = produto.imagem || '';
                        image.alt = '';

                        const info = document.createElement('span');
                        info.className = 'search-suggestion-info';

                        const name = document.createElement('span');
                        name.className = 'search-suggestion-name';
                        name.textContent = produto.nome;

                        const meta = document.createElement('span');
                        meta.className = 'search-suggestion-meta';
                        meta.textContent = `${produto.marca || produto.categoria || ''} - R$ ${Number(produto.preco).toFixed(2).replace('.', ',')}`;

                        info.append(name, meta);
                        link.append(image, info);
                        searchSuggestions.append(link);
                    });

                    searchSuggestions.classList.add('is-visible');
                    searchInput.setAttribute('aria-expanded', 'true');
                } catch (error) {
                    if (error.name !== 'AbortError') hideSuggestions();
                }
            }, 250);
        });

        searchInput.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') hideSuggestions();
        });

        document.addEventListener('click', (event) => {
            if (!searchSuggestions.contains(event.target) && !searchInput.contains(event.target)) {
                hideSuggestions();
            }
        });
    }
</script>

<style>
    .hover-text-primary:hover {
        color: #667eea !important;
        transition: color 0.3s;
    }
    
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }
    
    .text-transparent {
        color: transparent;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-outline-gradient {
        background: transparent;
        border: 2px solid #667eea;
        color: #667eea;
        transition: all 0.3s;
    }
    
    .btn-outline-gradient:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }
</style>
</body>
</html>