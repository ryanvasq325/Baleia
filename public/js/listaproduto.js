import { Requests } from "./Requests.js";
const tabela = new $('#tabela').DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    responsive: true,
    stateSave: true,
    select: true,
    processing: true,
    serverSide: true,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
        searchPlaceholder: 'Digite sua pesquisa...'
    },
    ajax: {
        url: '/produto/listproduto',
        type: 'POST'
    }
});

async function Update(id) {
    document.getElementById('id').value = id;
    const response = await Requests.SetForm('form').Post('/produto/alterar');
    if (!response.status) {
        Swal.fire({
            title: "Erro ao Alterar!",
            icon: "error",
            html: response.msg,
            timer: 3000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        return;
    }
    Swal.fire({
        title: "Alterado com sucesso!",
        icon: "success",
        html: response.msg,
        timer: 3000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    tabela.ajax.reload();
}
window.Update = Update;     