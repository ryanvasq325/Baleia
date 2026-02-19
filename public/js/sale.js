import { Validate } from "./Validate.js";
import { Requests } from "./Requests.js";

// Atualizar a cada segundo
setInterval(updateClock, 1000);
updateClock();
//Insere uma nova venda
async function InsertSale() {
  const valid = Validate.SetForm("form").Validate();
  if (!valid) {
    Swal.fire({
      icon: "error",
      title: "Erro",
      text: "Por favor, preencha os campos corretamente.",
      time: 2000,
      progressBar: true,
    });
    return;
  }
  try {
    const response = await Requests.SetForm("form").Post("/venda/insert");
    if (!response.status) {
      Swal.fire({
        icon: "error",
        title: "Erro",
        text: response.msg || "Ocorreu um erro ao inserir a venda.",
        time: 3000,
        progressBar: true,
      });
      return;
    }
      document.getElementById("amount").innerText = total_bruto.toLocaleString(
        "pt-BR",
        { style: "currency", currency: "BRL" },
      );
    }
    //Altera a ação do formulário para 'e' (editar) após a venda ser inserida com sucesso
    Action.value = "e";
    //Seta o ID da última venda inserida no banco de dados
    Id.value = response.id;
    //Atualiza a URL sem recarregar a página para refletir o ID da venda inserida
    window.history.pushState({}, "", `/venda/alterar/${response.id}`);

    await listItemSale();

    Swal.fire({
      icon: "success",
      title: "Sucesso",
      text: response.msg || "Venda inserida com sucesso!",
      time: 3000,
      progressBar: true,
    });

    document.querySelector(".btn-finalize")?.classList.remove("d-none");
    document.querySelector(".btn-cancel")?.classList.remove("d-none");

    const Desconto = document.querySelector("#desconto");

    if (Desconto) {
      Desconto.classList.remove("disabled");
      Desconto.disabled = false;
    }
    const Juros = document.querySelector("#juros");

    if (Juros) {
      Juros.classList.remove("disabled");
      Juros.disabled = false;
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Erro",
      text: error.message || "Ocorreu um erro ao inserir a venda.",
      time: 3000,
      progressBar: true,
    });
  }
}
async function listItemSale() {
  try {
    const response = await Requests.SetForm("form").Post("/venda/listitemsale");
    if (!response.status) {
      Swal.fire({
        icon: "error",
        title: "Erro",
        text: response.msg || "Ocorreu um erro ao listar os itens da venda.",
        time: 3000,
        progressBar: true,
      });
      return;
    }
    let total_liquido = parsedFloat(response?.sale?.total_liquido);
    let total_bruto = parsedFloat(response?.sale?.total_bruto);

    document.getElementById("total-amount").innerText =
      total_liquido.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL",
      });
    document.getElementById("amount").innerText = total_bruto.toLocaleString(
      "pt-BR",
      { style: "currency", currency: "BRL" },
    );
    let trs = "";
    response.data.forEach((item) => {
      let total_liquido = parseFloat(item?.total_liquido);
      let format_total_liquido = total_liquido
        .toLocaleString("pt-BR", {
          style: "currency",
          currency: "BRL",
        });
      trs += `
        <tr>
            <td>${item.id}</td>
            <td>${item.nome}</td>
            <td>${format_total_liquido}</td>
            <td>
                <button class="btn btn-danger">
                    Excluir cód: ${item.id} (Del)
                </button>
            </td>
        </tr>
        `;
    });
    document.getElementById("products-table-tbody").innerHTML = trs;
    document.getElementById("products-table-tbody").innerText = (response.data).length();
  } catch (error) {}
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

});
// Feedback visual para cliques
document.addEventListener("click", function (e) {
  if (e.target.matches("button")) {
    e.target.style.transition = "transform 0.1s";
  }
});

});
$(".form-select").on("select2:open", function (e) {
  let inputElement = document.querySelector(".select2-search__field");
  inputElement.placeholder = "Digite para pesquisar...";
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


