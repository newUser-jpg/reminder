<div>
    <!-- Waste no more time arguing what a good man should be, be one. - Marcus Aurelius -->
     <div class="compromisso">
    <div class="header">
        <div>
            <div class="titulo">{{ $titulo ?? 'Novo compromisso' }}</div>
            <div class="data">{{ $horario ?? '00:00' }} {{ $data ?? 'dd/mm/aaaa' }}</div>
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
            <input type="text" placeholder="Nome do compromisso" value="{{ $titulo ?? '' }}">
        </div>

        <div class="linha">
            <label>Horário:</label>
            <input type="time" value="{{ $horarioRaw ?? '' }}">
        </div>

        <div class="linha">
            <label>Data:</label>
            <input type="date" value="{{ $dataRaw ?? '' }}">
        </div>

        <div class="linha checkbox-container">
            <input type="checkbox">
            <label>Ativar lembrete</label>
        </div>

        <div class="linha">
            <label>Descrição:</label>
            <textarea rows="3">{{ $descricao ?? '' }}</textarea>
        </div>
    </div>
</div>

</div>