import { Validate } from "./Validate";

const insertItemButton = document.getElementById('insertItemButton');
// Atualizar relógio em tempo real
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

async function InsertSale() {
    const valid = Validate.SetForm('form').Validate();
    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Por favor, preencha os campos corretamente.',
            time: 2000,
            progressBar: true,
        });
        return;
    }
    try {
        const response = await Request.SetForm('form').Post('/venda/insert');
        if (!response.status) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.msg,
                time: 2000,
                progressBar: true,
            });
            return;
        }
        //Altere a ação do formulário para 'e' (editar) após a venda ser inserida com sucesso
        Action.value = 'e';
        //Seta o ID da última venda inserida no banco de dados
        Id.value = response.id;
        //Atualiza a URL sem recarregar venda inserida para refletir o ID da venda inserida
        window.history.pushState({}, '', `/venda/alterar/${response.id}`);

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Ocorreu um erro ao inserir a venda.',
            time: 2000,
            progressBar: true,
        })
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
// Feedback visual para cliques
document.addEventListener('click', function (e) {
    if (e.target.matches('button')) {
        e.target.style.transition = 'transform 0.1s';
    }
});

insertItemButton.addEventListener('click', async () => {
    alert('Clickou no item');
});

document.addEventListener('keydown', (e) => {
    //Bloque a ação de teclas F4, F8 e F9, F12 para evitar ações indesejadas
    //e.preventDefault();
    //Abrimos o modal de pesquisa de produto com a tecla F4
    if (e.key === 'F4') {
        const myModalEl = document.getElementById('pesquisaProdutoModal');
        const modal = new bootstrap.Modal(myModalEl);
        modal.show();
    }
    //Fechamos o modal de pesquisa de produto com a tecla F8
    if (e.key === 'F8') {
        const myModalEl = document.getElementById('pesquisaProdutoModal');
        const modal = new bootstrap.Modal(myModalEl);
        modal.hide();
    }
    //Inserimos o item da venda com a tecla F9
    if (e.key === 'F9') {
        alert('olá');
    }
});

$('#pesquisa').select2({
    theme: 'bootstrap-5',
    placeholder: "Selecione um produto",
    language: "pt-BR",
    ajax: {
        url: '/produto/listproductdata',
        type: 'POST'
    }
});
$('.form-select').on('select2:open', function (e) {
    let inputElement = document.querySelector('.select2-search__field');
    inputElement.placeholder = 'Digite para pesquisar...';
    inputElement.focus();
});