<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pomodoro - Minimal</title>
<link rel="stylesheet" href="css/pomodoro.css">
</head>
<body>
  <x-sidebar />
  <audio id="somTroca" src="sounds/single-church-bell-2-352062.mp3" preload="auto"></audio>


  

<div class="main-content">
<div class="timer-container">
  <div class="cycle-box">
  <label for="cycleCount">Ciclos:</label>
  <input type="number" id="cycleCount" value="4" min="1">
  </div>
  <div class="circle" id="circle" aria-label="Tempo principal e descanso">
    <svg class="progress-ring" width="260" height="260">
        <circle class="progress-bg" cx="130" cy="130" r="120" />
        <circle class="progress-bar" cx="130" cy="130" r="120" />
    </svg>
    <span id="mainTime">25:00</span>
    <span id="breakTimeDisplay">5:00</span>
  </div>

  <div class="buttons">
    <button id="startBtn">Começar</button>
    <button id="stopBtn">Parar</button>
  </div>
</div>
</div>

<!-- Modal com apenas os inputs de time (sem botão salvar) -->
<div id="timeModal" class="modal hidden" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <h2 id="modalTitle">Definir Tempo</h2>

    <div class="linha">
      <label for="activeInput">Tempo de atividade</label>
      <input type="text" id="activeInput" placeholder="25:00" pattern="\\d{2}:\\d{2}">
    </div>

    <div class="linha">
      <label for="breakInput">Tempo de descanso</label>
      <input type="text" id="breakInput" placeholder="05:00" pattern="\\d{2}:\\d{2}">
    </div>

    <p style="font-size:13px;color:var(--muted);margin:6px 0 0">
      Feche a janela para aplicar (clique fora ou pressione Esc)
    </p>
<script>
let ciclosRestantes = 0;
const circle = document.getElementById("circle");
const modal = document.getElementById("timeModal");
const activeInput = document.getElementById("activeInput");
const breakInput = document.getElementById("breakInput");

const mainTime = document.getElementById("mainTime");
const breakLabel = document.getElementById("breakTimeDisplay");

function carregarTempos() {
    const salvoAtivo = localStorage.getItem("pomodoro_ativo");
    const salvoDesc = localStorage.getItem("pomodoro_descanso");

    if (salvoAtivo) {
        document.getElementById("activeInput").value = salvoAtivo;
        const [m,s] = salvoAtivo.split(":");
        document.getElementById("mainTime").textContent = `${m}:${s ?? "00"}`;
    }

    if (salvoDesc) {
        document.getElementById("breakInput").value = salvoDesc;
        const [m,s] = salvoDesc.split(":");
        document.getElementById("breakTimeDisplay").textContent = `${m}:${s ?? "00"}`;
    }
}

carregarTempos();

function aplicarTempoDoModal() {
    const ativo = document.getElementById("activeInput").value;
    const descanso = document.getElementById("breakInput").value;

    if (ativo) {
    const [min, sec] = ativo.split(":");
    mainTime.textContent = `${min.padStart(2, "0")}:${sec.padStart(2, "0")}`;
    }


    if (descanso) {
    const [min, sec] = descanso.split(":");
    breakLabel.textContent = `${min.padStart(2, "0")}:${sec.padStart(2, "0")}`;
    }

}


// Abrir modal ao clicar no círculo
circle.addEventListener("click", () => {
    modal.classList.remove("hidden");
});

// Fechar modal ao clicar fora
modal.addEventListener("click", e => {
    if (e.target === modal) {
        applyTimes();
        modal.classList.add("hidden");
        aplicarTempoDoModal();
        salvarTempos();
    }
});

// Fechar modal com ESC
document.addEventListener("keydown", e => {
    if (e.key === "Escape") {
        applyTimes();
        modal.classList.add("hidden");
        aplicarTempoDoModal();
        salvarTempos();
    }
});

function salvarTempos() {
    const ativo = document.getElementById("activeInput").value;
    const descanso = document.getElementById("breakInput").value;

    localStorage.setItem("pomodoro_ativo", ativo);
    localStorage.setItem("pomodoro_descanso", descanso);
}


// Aplicar valores ao círculo
function applyTimes() {
    if (activeInput.value) {
        const [h, m] = activeInput.value.split(":");
        //mainTime.textContent = `${parseInt(h)*60 + parseInt(m)}`.padStart(2,"0") + ":00";

        // Converte formato HH:MM para MM:SS
        mainTime.textContent = `${h.padStart(2,"0")}:${m.padStart(2,"0")}`;
    }

    if (breakInput.value) {
        const [h, m] = breakInput.value.split(":");
        breakLabel.textContent = `${h.padStart(2,"0")}:${m.padStart(2,"0")}`;
    }
}


let interval = null;
let modo = "atividade";

const mainEl = document.getElementById("mainTime");
const breakEl = document.getElementById("breakTimeDisplay");
const barra = document.querySelector(".progress-bar");
const somTroca = document.getElementById("somTroca");

// ======== Funções utilitárias ========

function format(n) {
    return n < 10 ? "0" + n : n;
}

function getSeconds(text) {
    const [m, s] = text.split(":").map(Number);
    return m * 60 + s;
}

function drawBar(progress) {
    const circ = 2 * Math.PI * 120;
    barra.style.strokeDashoffset = circ - circ * progress;
}

function updateCircle(sec, target) {
    const m = Math.floor(sec / 60);
    const s = sec % 60;
    target.textContent = `${format(m)}:${format(s)}`;
}

// ======== Timer ========

function iniciarTimer() {

    if (interval) return;

    ciclosRestantes = parseInt(document.getElementById("cycleCount").value);

    if (isNaN(ciclosRestantes) || ciclosRestantes < 1) {
        ciclosRestantes = 1;
    }

    let tempoAtividade = getSeconds(mainEl.textContent);
    let tempoDescanso  = getSeconds(breakEl.textContent);

    const inicioAtividade = tempoAtividade;
    const inicioDescanso  = tempoDescanso;

    interval = setInterval(() => {

        if (modo === "atividade") {
            tempoAtividade--;
            updateCircle(tempoAtividade, mainEl);
            drawBar(1 - tempoAtividade / inicioAtividade);

            if (tempoAtividade <= 0) {
                modo = "descanso";
                somTroca.play();
            }
        }

        else if (modo === "descanso") {
            tempoDescanso--;
            updateCircle(tempoDescanso, breakEl);
            drawBar(1 - tempoDescanso / inicioDescanso);

            if (tempoDescanso <= 0) {
                somTroca.play();

                ciclosRestantes--;

                if (ciclosRestantes <= 0) {
                    pararTimer();
                    modo = "atividade";
                    alert("Ciclos concluídos!");
                    return;
                }

                // Reiniciar ciclo
                modo = "atividade";
                tempoAtividade = inicioAtividade;
                tempoDescanso  = inicioDescanso;

                updateCircle(tempoAtividade, mainEl);
                updateCircle(tempoDescanso, breakEl);

                drawBar(0);
            }
        }

    }, 1000);
}


function pararTimer() {
    clearInterval(interval);
    interval = null;
}

// ======== Eventos ========

document.getElementById("startBtn").addEventListener("click", iniciarTimer);
document.getElementById("stopBtn").addEventListener("click", pararTimer);


</script>
</body>