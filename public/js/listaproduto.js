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
    select: true, // Isso permite selecionar a linha
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

// --- LÓGICA DE ATALHOS ---
document.addEventListener('keydown', function (e) {
    
    // F2 - Ir para Cadastro
    if (e.key === 'F2') {
        e.preventDefault();
        window.location.href = '/produto/cadastro';
    }
});

async function Delete(id) {
    document.getElementById('id').value = id;
    const response = await Requests.SetForm('form').Post('/produto/delete');
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

window.Delete = Delete;