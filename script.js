// Funções globais
function formatarPreco(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

// Animações de scroll
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animação de fade-in para elementos
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });
    
    document.querySelectorAll('.produto-card, .section-title').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
});

// Toast notifications
function mostrarToast(mensagem, tipo = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo}`;
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    toast.style.animation = 'slideIn 0.5s';
    toast.innerHTML = mensagem;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.5s';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 500);
    }, 3000);
}

// Confirmação de ações
function confirmarAcao(mensagem, callback) {
    if (confirm(mensagem)) {
        callback();
    }
}

// Loading spinner
function mostrarLoading() {
    const loading = document.createElement('div');
    loading.className = 'loading-spinner';
    loading.style.position = 'fixed';
    loading.style.top = '0';
    loading.style.left = '0';
    loading.style.width = '100%';
    loading.style.height = '100%';
    loading.style.background = 'rgba(0,0,0,0.5)';
    loading.style.display = 'flex';
    loading.style.alignItems = 'center';
    loading.style.justifyContent = 'center';
    loading.style.zIndex = '9999';
    loading.innerHTML = '<div style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #1e3c72; border-radius: 50%; animation: spin 1s linear infinite;"></div>';
    
    document.body.appendChild(loading);
    return loading;
}

function esconderLoading(loading) {
    document.body.removeChild(loading);
}

// Validação de formulários
function validarEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11) return false;
    
    // Validação básica (você pode implementar uma validação mais completa)
    return true;
}

// Carrinho de compras
function atualizarQuantidade(id, acao) {
    fetch(`atualizar_carrinho.php?id=${id}&acao=${acao}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
}

// Busca de CEP (já implementada no checkout)
// Adicione mais funções conforme necessário