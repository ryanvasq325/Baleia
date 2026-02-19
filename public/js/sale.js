import { Validate } from "./Validate.js";
import { Requests } from "./Requests.js";

const Action = document.getElementById('acao');
const Id = document.getElementById('id');
const insertItemButton = document.getElementById('insertItemButton');
// Atualizar relógio em tempo real
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const days = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado'];

    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

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
//Insere uma nova venda
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
        const response = Action.value === 'c' ?
            await Requests.SetForm('form').Post('/venda/insert')
            :
            await Requests.SetForm('form').Post('/venda/update');
        if (!response.status) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.msg || 'Ocorreu um erro ao inserir a venda.',
                time: 3000,
                progressBar: true,
            });
            return;
        }
        //Altera a ação do formulário para 'e' (editar) após a venda ser inserida com sucesso
        Action.value = 'e';
        //Seta o ID da última venda inserida no banco de dados
        Id.value = response.id;
        //Atualiza a URL sem recarregar a página para refletir o ID da venda inserida
        window.history.pushState({}, '', `/venda/alterar/${response.id}`);
        //Lista todos os item vendido, e quantidade de item da venda, e total da venda.
        await listItemSale();
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.message || 'Ocorreu um erro ao inserir a venda.',
            time: 3000,
            progressBar: true,
        });
    }
}
//Adicionar o item a venda.
async function InsertItemSale() {
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
        const response = await Requests.SetForm('form').Post('/venda/insertitem');
        if (!response.status) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.msg || 'Ocorreu um erro ao inserir a venda.',
                time: 3000,
                progressBar: true,
            });
            return;
        }
        //Atualiza a a tabela de itens da venda.

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.message || 'Ocorreu um erro ao inserir a venda.',
            time: 3000,
            progressBar: true,
        });
    }
}
//
async function listItemSale() {
    try {
        const response = await Requests.SetForm('form').Post('/venda/listitemsale');
        if (!response.status) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.msg || 'Não foi possivel listar os dados da venda',
                time: 2000,
                progressBar: true,
            });
            return;
        }
        let total_liquido = parseFloat(response?.sale?.total_liquido);
        let total_bruto = parseFloat(response?.sale?.total_bruto);

        document.getElementById('total-amount').innerText = total_liquido
            .toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

        document.getElementById('amount').innerText = total_bruto
            .toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        let trs = '';
        response.data.forEach(item => {
            let total_liquido = parseFloat(item?.total_liquido)
                .toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            trs += `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.nome}</td>
                    <td>${total_liquido}</td>
                    <td>
                        <button class="btn btn-danger">
                            Excluir cód: ${item.id} (Del)
                        </button>
                    </td>
                </tr>
           `;
        });
        //Atualizamos o itens da venda na tabela
        document.getElementById('products-table-tbody').innerHTML = trs;
        //Atualizamos o total de itens vencido.
        document.getElementById('product-count').innerText = `Itens ${(response.data).length}`;

    } catch (error) {

    }
}
// Event Listeners para botões de adicionar
document.addEventListener('DOMContentLoaded', async () => {
    if (Action.value === 'e') {
        await listItemSale();
    }
});
// Feedback visual para cliques
document.addEventListener('click', function (e) {
    if (e.target.matches('button')) {
        e.target.style.transition = 'transform 0.1s';
    }
});
insertItemButton.addEventListener('click', async () => {
    //Salva os dados da venda
    await InsertSale();
    //Salva o item da venda
    await InsertItemSale();
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
const gerenciarInterfaceVenda = () => {
  const painel = document.querySelector(".cart-section"); // Selecionando pela classe do CSS

  const corpoTabela = document.querySelector(".products-table tbody");

  if (!painel || !inputAcao) return;

  const acao = inputAcao.value.trim().toLowerCase();
  const totalLinhas = corpoTabela ? corpoTabela.rows.length : 0;

  // Se a ação for 'c' (cadastro) e a tabela estiver vazia, esconde.
};

setInterval(gerenciarInterfaceVenda, 200);

gerenciarInterfaceVenda();