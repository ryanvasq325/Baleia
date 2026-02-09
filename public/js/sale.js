import { Requests } from "./Requests.js";

const tabela = $('#tabela').DataTable({
    paging: true,
    serverSide: true,
    responsive: true,
    // dom: 't' mostra apenas a TABELA. 
    // dom: 'tip' mostra Tabela, Info (embaixo) e Paginação (embaixo).
    // Isso evita que ele crie campos de busca duplicados no topo.
    dom: 'rtip', 
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
    },
    ajax: {
        url: '/venda/listsale',
        type: 'POST'
    },
    // Opcional: Ajuste as colunas para não quebrarem o layout
    columnDefs: [
        { className: "text-center", targets: "_all" }
    ]
});

async function Delete(id) {
    document.getElementById('id').value = id;
    const response = await Requests.SetForm('form').Post('/venda/delete');
    if (!response.status) {
        Swal.fire({
            title: "Erro ao remover!",
            icon: "error",
            html: response.msg,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }
    Swal.fire({
        title: "Removido com sucesso!",
        icon: "success",
        html: response.msg,
        timer: 3000,
        timerProgressBar: true
    });
    tabela.ajax.reload();
}
function updateClock() {
    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const days = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira',
        'Quinta-Feira', 'Sexta-Feira', 'Sábado'];
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    const dayName = days[now.getDay()];
    const day = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();

    const timeElement = document.querySelector('.time');
    const dateElement = document.querySelector('.date');

    if (timeElement) {
        timeElement.textContent = `${hours}:${minutes}:${seconds}`;
    }

    if (dateElement) {
        dateElement.textContent = `${dayName}, ${day} De ${month} De ${year}`;
    }
}

// Atualizar a cada segundo
setInterval(updateClock, 1000);
updateClock();

// Gerenciamento do carrinho
let cart = [];
let paymentMethod = 'dinheiro';
let discount = { type: 'valor', amount: 0 };

// Adicionar produto ao carrinho
function addToCart(code, description, price) {
    const existingItem = cart.find(item => item.code === code);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            code: code,
            description: description,
            price: price,
            quantity: 1
        });
    }

    updateCart();
}

// Atualizar visualização do carrinho
function updateCart() {
    const cartEmpty = document.querySelector('.cart-empty');

    if (cart.length === 0) {
        cartEmpty.style.display = 'block';
    } else {
        cartEmpty.style.display = 'none';
        // Aqui você pode adicionar a lógica para mostrar os itens do carrinho
    }

    updateTotals();
}

// Calcular e atualizar totais
function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    let discountAmount = 0;
    if (discount.type === 'valor') {
        discountAmount = discount.amount;
    } else {
        discountAmount = (subtotal * discount.amount) / 100;
    }

    const total = subtotal - discountAmount;

    const subtotalElement = document.querySelector('.subtotal .amount');
    const totalElement = document.querySelector('.total-amount');

    if (subtotalElement) {
        subtotalElement.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
    }

    if (totalElement) {
        totalElement.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
    }
}

// Event Listeners para botões de adicionar
document.addEventListener('DOMContentLoaded', function () {
    // Botões de adicionar produto
    const addButtons = document.querySelectorAll('.btn-add');
    addButtons.forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const code = row.cells[0].textContent;
            const description = row.cells[1].textContent;
            const priceText = row.cells[2].textContent;
            const price = parseFloat(priceText.replace('R$', '').replace(',', '.').trim());

            addToCart(code, description, price);

            // Feedback visual
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 100);
        });
    });

    // Botões de método de pagamento
    const paymentButtons = document.querySelectorAll('.payment-btn');
    paymentButtons.forEach(button => {
        button.addEventListener('click', function () {
            paymentButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const method = this.querySelector('span').textContent.toLowerCase();
            paymentMethod = method;
        });
    });

    // Campo de desconto em valor
    const discountInputRs = document.querySelector('.discount-input-rs');
    if (discountInputRs) {
        discountInputRs.addEventListener('click', function () {
            discount.type = 'valor';
            updateInputStyles();
        });
    }

    // Campo de desconto em porcentagem
    const discountInputPercent = document.querySelector('.discount-input-percent');
    if (discountInputPercent) {
        discountInputPercent.addEventListener('click', function () {
            discount.type = 'percentual';
            updateInputStyles();
        });
    }

    // Valor do desconto
    const discountValue = document.querySelector('.discount-value');
    if (discountValue) {
        discountValue.addEventListener('input', function () {
            discount.amount = parseFloat(this.value) || 0;
            updateTotals();
        });
    }

    // Botão de buscar
    const searchButton = document.querySelector('.btn-search');
    const searchInput = document.querySelector('.search-input');

    if (searchButton) {
        searchButton.addEventListener('click', function () {
            const searchTerm = searchInput.value.toLowerCase();
            filterProducts(searchTerm);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.toLowerCase();
                filterProducts(searchTerm);
            }
        });
    }

    // Botão finalizar venda
    const finalizeButton = document.querySelector('.btn-finalize');
    if (finalizeButton) {
        finalizeButton.addEventListener('click', function () {
            if (cart.length === 0) {
                alert('Carrinho vazio! Adicione produtos antes de finalizar.');
                return;
            }

            const total = document.querySelector('.total-amount').textContent;
            const confirmation = confirm(`Finalizar venda no valor de ${total}?`);

            if (confirmation) {
                alert('Venda finalizada com sucesso!');
                cart = [];
                discount = { type: 'valor', amount: 0 };
                document.querySelector('.discount-value').value = '0';
                updateCart();
            }
        });
    }

    // Botão cancelar venda
    const cancelButton = document.querySelector('.btn-cancel');
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            if (cart.length === 0) {
                return;
            }

            const confirmation = confirm('Deseja cancelar a venda atual?');

            if (confirmation) {
                cart = [];
                discount = { type: 'valor', amount: 0 };
                document.querySelector('.discount-value').value = '0';
                updateCart();
                alert('Venda cancelada!');
            }
        });
    }
});

// Filtrar produtos na tabela
function filterProducts(searchTerm) {
    const rows = document.querySelectorAll('.products-table tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const code = row.cells[0].textContent.toLowerCase();
        const description = row.cells[1].textContent.toLowerCase();

        if (code.includes(searchTerm) || description.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Atualizar contador de produtos
    const productCount = document.querySelector('.product-count');
    if (productCount) {
        productCount.textContent = `${visibleCount} produtos`;
    }
}

// Atualizar estilos dos inputs de desconto
function updateInputStyles() {
    const inputRs = document.querySelector('.discount-input-rs');
    const inputPercent = document.querySelector('.discount-input-percent');

    if (discount.type === 'valor') {
        inputRs.style.borderColor = 'var(--primary-blue)';
        inputRs.style.background = 'var(--white)';
        inputPercent.style.borderColor = 'var(--gray-300)';
        inputPercent.style.background = 'var(--gray-50)';
    } else {
        inputPercent.style.borderColor = 'var(--primary-blue)';
        inputPercent.style.background = 'var(--white)';
        inputRs.style.borderColor = 'var(--gray-300)';
        inputRs.style.background = 'var(--gray-50)';
    }
}

// Atalhos de teclado
document.addEventListener('keydown', function (e) {
    // F2 - Focar no campo de busca
    if (e.key === 'F2') {
        e.preventDefault();
        document.querySelector('.search-input')?.focus();
    }

    // F9 - Finalizar venda
    if (e.key === 'F9') {
        e.preventDefault();
        document.querySelector('.btn-finalize')?.click();
    }

    // Esc - Cancelar venda
    if (e.key === 'Escape') {
        e.preventDefault();
        document.querySelector('.btn-cancel')?.click();
    }
});

// Feedback visual para cliques
document.addEventListener('click', function (e) {
    if (e.target.matches('button')) {
        e.target.style.transition = 'transform 0.1s';
    }
});

console.log('Sistema de Vendas - Carregado com sucesso!');

window.Delete = Delete;

