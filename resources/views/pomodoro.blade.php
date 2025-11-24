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

    <script src="scripts/timer.js"></script>
</body>