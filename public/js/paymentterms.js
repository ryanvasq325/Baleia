import { Requests } from "./Requests.js";

const insertPaymentTermsButton = document.getElementById('insertPaymentTermsButton');
const insertInstallmentButton = document.getElementById('insertInstallmentButton');
const Action = document.getElementById('acao');

async function insertPaymentTerms() {
    ...
}

insertPaymentTermsButton.addEventListener('click', async () => {
    await insertPaymentTerms();
});

insertInstallmentButton.addEventListener('click', async () => {
    alert('Inserir parcelamento');
});
