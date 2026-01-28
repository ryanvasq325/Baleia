class DataTables {
    static id;
    static url;
    static method;
    static requestVariables = [];
    static conf = {
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
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json',
            searchPlaceholder: 'Digite sua pesquisa...'
        },
        ajax: {
            url: this.url,
            type: this.method,
            data: () => {
                // Adiciona as variáveis de requisição
                this.requestVariables.forEach(variable => {
                    requestData[variable] = $('#' + variable).val();
                });
            }
        },
        layout: {
            topStart: 'search',
            topEnd: 'pageLength',
            bottomStart: 'info',
            bottomEnd: 'paging'
        },
        // ✅ Aqui aplicamos a estilização após a tabela estar pronta
        initComplete: function () {
            setTimeout(() => {
                // Remove o label "Pesquisar"
                const label = document.querySelector('.dt-search label');
                if (label) {
                    label.remove(); // Remove completamente do DOM
                }
                // Seleciona div que contém o campo de pesquisa
                const searchDiv = document.querySelector('.row > div.dt-layout-start');
                if (searchDiv) {
                    searchDiv.classList.remove('col-md-auto');
                    searchDiv.classList.add('col-lg-6', 'col-md-6', 'col-sm-12');
                }
                const divSearch = document.querySelector('.dt-search');
                if (divSearch) {
                    divSearch.classList.add('w-100'); // ou w-100, w-75 etc.
                }

                const input = document.querySelector('#dt-search-0');
                if (input) {
                    input.classList.remove('form-control-sm'); // ou w-100, w-75 etc.
                    input.classList.add('form-control-md', 'w-100'); // ou w-100, w-75 etc.
                    // Remove margem e padding da esquerda
                    input.style.marginLeft = '0';
                    input.focus();
                }
                const pageLength = document.querySelector('#dt-length-0');
                if (pageLength) {
                    pageLength.classList.add('form-select-md'); // ou form-select-sm, dependendo do tamanho desejado
                }
            }, 100);
        }
    };
    static SetId(id) {
        this.id = id;
        return this;
    }
    static async Post(url) {
        this.url = url;
        this.method = 'POST';
        let table = new $("#" + this.id).DataTable(this.conf);
        $(`#${this.id} tbody`).on('click', 'tr', function () {
            $(this).toggleClass('selected');
        });
        $('#button').click(function () {
            alert(table.rows('.selected').data().length + ' row(s) selected');
        });
        window.addEventListener('resize', () => {
            table.ajax.reload(null, false);
        });
        table.ajax.reload(null, false);
        return table;
    }
}
export { DataTables };