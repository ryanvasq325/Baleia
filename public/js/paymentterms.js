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
        }).then((result) => {

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
        Swal.fire({
            icon: "success",
            title: "Sucesso",
            text: response.msg,
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        }).then((result) => {
            //Carrega os dados do parcelamento da condição de pagamento.
        });
    } catch (error) {
        console.log(error)
    }
}

async function loadDataInstallments() {
    try {
        [
            {
                parcels: 1,
                intervalor: 30,
                alterar_vencimento_conta: '0'
            },
            {
                parcels: 2,
                intervalor: 30,
                alterar_vencimento_conta: '0'
            }
        ]
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
                    <button class="btn btn-danger" onclick="deleteInstallment(${item.id})">Excluir</button>
                </td>
            </tr>
            `;
        });
    } catch (error) {
        console.log(error)
    }
}

insertPaymentTermsButton.addEventListener('click', async () => {
    await insertPaymentTerms();
});
insertInstallmentButton.addEventListener('click', async () => {
    await insertInstallment();
});