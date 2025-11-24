let interval = null;
let modo = "atividade";
let ciclosRestantes = 1;

const mainEl = document.getElementById("mainTime");
const breakEl = document.getElementById("breakTimeDisplay");
const barra = document.querySelector(".progress-bar");
const somTroca = document.getElementById("somTroca");
const startBtn = document.getElementById("startBtn");
const stopBtn  = document.getElementById("stopBtn");
const cycleInput = document.getElementById("cycleCount");

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

function resetTimerToInitial() {
    const ativo = localStorage.getItem("pomodoro_ativo") || "25:00";
    const descanso = localStorage.getItem("pomodoro_descanso") || "05:00";

    mainEl.textContent = ativo;
    breakEl.textContent = descanso;
    drawBar(0);
    modo = "atividade";
}

// ======== Timer ========

function iniciarTimer() {

    // Se o botão estiver no modo REINICIAR
    if (startBtn.dataset.state === "restart") {
        pararTimer();
        resetTimerToInitial();

        startBtn.textContent = "Começar";
        startBtn.dataset.state = "start";
        return;
    }

    // Evitar múltiplos timers
    if (interval) return;

    // Ao clicar em COMEÇAR → vira REINICIAR
    startBtn.textContent = "Reiniciar";
    startBtn.dataset.state = "restart";

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

function resetTimerToInitial() {
    const ativo = localStorage.getItem("pomodoro_ativo") || "25:00";
    const descanso = localStorage.getItem("pomodoro_descanso") || "05:00";

    mainEl.textContent = ativo;
    breakEl.textContent = descanso;
    drawBar(0);
    modo = "atividade";
}


function pararTimer() {
    clearInterval(interval);
    interval = null;

    
    // Voltar o botão para COMEÇAR
    startBtn.textContent = "Começar";
    startBtn.dataset.state = "start";
    
}

// ======== Eventos ========

startBtn.dataset.state = "start";
startBtn.addEventListener("click", iniciarTimer);
stopBtn.addEventListener("click", pararTimer);
