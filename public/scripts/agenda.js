   // Solicitar permissão para notificações
if (Notification.permission !== "granted") {
    Notification.requestPermission();
}

function enviarNotificacao(titulo, corpo) {
    if (Notification.permission === "granted") {
        new Notification(titulo, {
            body: corpo,
            icon: "https://img.icons8.com/fluency/96/calendar.png"
        });
    }
}

// --- Sistema de abertura/fechamento ---
function ativarToggle(compromisso) {
    compromisso.querySelector(".header").addEventListener("click", () => {
        compromisso.classList.toggle("aberto");
    });
}

// --- Sistema de salvar no localStorage ---
function salvarTudo() {
    const lista = document.getElementById("lista-compromissos");
    const compromissos = [];

    lista.querySelectorAll(".compromisso").forEach(c => {
        compromissos.push({
            nome: c.querySelector(".campo-nome").value,
            horario: c.querySelector(".campo-horario").value,
            data: c.querySelector(".campo-data").value,
            lembrete: c.querySelector(".campo-lembrete").checked,
            descricao: c.querySelector(".campo-descricao").value
        });
    });

    localStorage.setItem("compromissos", JSON.stringify(compromissos));
    // Reagendar todos os lembretes
    compromissos.forEach(c => agendarLembrete(c));

}

// --- Carregar compromissos salvos ---
function carregarCompromissos() {
    const lista = document.getElementById("lista-compromissos");
    const msgVazio = document.getElementById("msg-vazio");
    const dados = JSON.parse(localStorage.getItem("compromissos") || "[]");

    if (dados.length === 0) {
        msgVazio.style.display = "block";
        return;
    }

    msgVazio.style.display = "none";

    dados.forEach(item => {criarCompromisso(item), agendarLembrete(item);
});
}

// --- Criar compromisso ---
function criarCompromisso(dados = null) {
    const lista = document.getElementById("lista-compromissos");
    const msgVazio = document.getElementById("msg-vazio");

    msgVazio.style.display = "none";

    const novo = document.createElement("div");
    novo.classList.add("compromisso", "aberto");

    novo.innerHTML = `
        <div class="header">
            <div>
                <div class="titulo">${dados?.nome || "Novo compromisso"}</div>
                <div class="data">${dados?.horario || "00:00"} ${dados?.data || "dd/mm/aaaa"}</div>
            </div>

            <div class="seta">
                <svg width="24" height="24" fill="#fff">
                    <polygon points="6,9 12,15 18,9" />
                </svg>
            </div>
        </div>

        <div class="conteudo">
            <div class="linha">
                <label>Nome:</label>
                <input class="campo-nome" type="text" value="${dados?.nome || ""}">
            </div>

            <div class="linha">
                <label>Horário:</label>
                <input class="campo-horario" type="time" value="${dados?.horario || ""}">
            </div>

            <div class="linha">
                <label>Data:</label>
                <input class="campo-data" type="date" value="${dados?.data || ""}">
            </div>

            <div class="linha checkbox-container">
                <input class="campo-lembrete" type="checkbox" ${dados?.lembrete ? "checked" : ""}>
                <label>Ativar lembrete</label>
            </div>

            <div class="linha">
                <label>Descrição:</label>
                <textarea class="campo-descricao" rows="3">${dados?.descricao || ""}</textarea>
            </div>

            <button class="btn-salvar">Salvar</button>
            <button class="btn-excluir">Excluir</button>
        </div>
    `;

    // Ativar abrir/fechar
    ativarToggle(novo);

    // Botão salvar
    novo.querySelector(".btn-salvar").addEventListener("click", () => {
        novo.querySelector(".titulo").textContent =
            novo.querySelector(".campo-nome").value || "Sem nome";

        novo.querySelector(".data").textContent =
            `${novo.querySelector(".campo-horario").value || "00:00"} ${
                novo.querySelector(".campo-data").value || "dd/mm/aaaa"
            }`;

        salvarTudo();
    });

    // Botão excluir
    novo.querySelector(".btn-excluir").addEventListener("click", () => {
        novo.remove();
        salvarTudo();

        if (lista.children.length === 0)
            document.getElementById("msg-vazio").style.display = "block";
    });

    lista.appendChild(novo);
}

// --- Botão "+" ---
document.getElementById("btn-add").addEventListener("click", () => {
    criarCompromisso();
});

function agendarLembrete(compromisso) {
    const horario = compromisso.horario;
    const data = compromisso.data;
    const lembreteAtivado = compromisso.lembrete;

    if (!lembreteAtivado || !horario || !data) return;

    const dataCompleta = new Date(`${data}T${horario}:00`);

    // Se horário já passou, ignora
    if (dataCompleta <= new Date()) return;

    const agora = Date.now();
    const tempoRestante = dataCompleta - agora;

    setTimeout(() => {
        enviarNotificacao(
            `Lembrete: ${compromisso.nome}`,
            compromisso.descricao || "Você tem um compromisso agora!"
        );
    }, tempoRestante);
}


// --- Carregar ao abrir ---
carregarCompromissos();