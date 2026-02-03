import { Requests } from "./Requests.js";
const insertPaymentTermsButton = document.getElementById('insertPaymentTermsButton');
const insertInstallmentButton = document.getElementById('insertInstallmentButton');
const Action = document.getElementById('acao');
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

        });
    } catch (error) {
        console.log(error)
    }
}
async function insertInstallment() {
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

        });
    } catch (error) {
        console.log(error)
    }
}

insertPaymentTermsButton.addEventListener('click', async () => {
    await insertPaymentTerms();
});
insertInstallmentButton.addEventListener('click', async () => {
    await insertPaymentTerms();
});