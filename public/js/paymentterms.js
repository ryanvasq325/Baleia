import { Requests } from "./Requests.js";
const insertPaymentTermsButton = document.getElementById('insertPaymentoTermsButton');
const insertInstallmentButton = document.getElementById('insertInstallmentButton');
const Action = document.getElementById('acao');
const Id = document.getElementById('id');
//Salva os dados do termo de pagamento (Insert ou Update).
async function insertPaymentTerms() {
    try {
        const response = (Action.value === 'c') ?
            await Requests.SetForm('form').Post('/pagamento/insert')
            :
            await Requests.SetForm('form').Post('/pagamento/update');
        if (!response.status) {
            Swal.fire({
                icon: "error",
                title: "Restrição",
                text: response.msg,
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            return;
        }
        if (Action.value === 'e') {
            Swal.fire({
                icon: "success",
                title: "Sucesso",
                text: response.msg,
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then(() => {
                window.location.replace('/pagamento/lista');
            });
            return;
        }
        Action.value = 'e';
        //Seta o ID retornado do back-end.
        Id.value = response.id;
        //Se o usuário estiver em: https://app.exemplo.com:8080/dashboard, Então: https://app.exemplo.com:8080
        const baseUrl = window.location.origin;
        //Constrói dinamicamente uma URL completa usando:
        const redirectUrl = `${baseUrl}/pagamento/alterar/${response.id}`;
        //Muda o valor da ação - (e) = editar
        //Altera a URL do navegador sem recarregar a página.
        window.history.pushState({}, '', redirectUrl);
        Swal.fire({
            icon: "success",
            title: "Sucesso",
            text: response.msg,
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    } catch (error) {
        console.log(error)
    }
}

async function insertInstallment() {
    try {
        if (Action.value === 'c') {
            await insertPaymentTerms();
        }
        const response = await Requests.SetForm('form').Post('/pagamento/insertinstallment');
        if (!response.status) {
            Swal.fire({
                icon: "error",
                title: "Restrição",
                text: response.msg,
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            return;
        }
        await loadDataInstallments();
    } catch (error) {
        console.log(error)
    }
}

async function loadDataInstallments() {
    try {
        if (Action.value === 'c') {
            document.getElementById('tbInstallments').innerHTML = '';
            document.getElementById('tbInstallments').innerHTML = `
                <tr>
                    <td class="text-center" colspan="5">Nenhuma parcela cadastrada</td>
                </tr>
            `;
            return;
        }
        const response = await Requests.SetForm('form').Post('/pagamento/loaddatainstallments');
        //Variavel para guardar os dados da linha da tabela de parcelamento.
        let trs = '';
        response.data.forEach(item => {
            trs += `
            <tr id="trinstallment${item.id}">
                <td>${item.id}</td>
                <td>${item.parcela} X</td>
                <td>${item.intervalor} (Dias)</td>
                <td>${item.alterar_vencimento_conta} (Dias)</td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="deleteInstallment(${item.id})">Excluir</button>
                </td>
            </tr>
            `;
        });
        document.getElementById('tbInstallments').innerHTML = '';
        document.getElementById('tbInstallments').innerHTML = trs;
    } catch (error) {
        console.log(error)
    }
}

async function deleteInstallment(id) {
    document.getElementById('id_parcelamento').value = id;
    try {
        const response = await Requests.SetForm('form').Post('/pagamento/deleteinstallment');
        if (!response.status) {
            Swal.fire({
                icon: "error",
                title: "Restrição",
                text: response.msg,
                timer: 3000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            return;
        }
        document.getElementById(`trinstallment${id}`).remove();
    } catch (error) {
        console.log(error);
    }
}

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

insertPaymentTermsButton.addEventListener('click', async () => {
    await insertPaymentTerms();
});
insertInstallmentButton.addEventListener('click', async () => {
    await insertInstallment();
});
window.deleteInstallment = deleteInstallment;
//Sempre que a pagina for carregada, carrega os dados das parcelas.
await loadDataInstallments();