<div>
    <div class="sidebar">

        <!-- Agenda -->
        <a href="{{ route('index') }}" class="sidebar-link">
            <div class="sidebar-icon {{ request()->routeIs('agenda') ? 'active' : '' }}"
                style="background-image: url('https://img.icons8.com/fluency/100/calendar.png');">
            </div>
        </a>

        <!-- Pomodoro -->
        <a href="{{ route('pomodoro') }}" class="sidebar-link">
            <div class="sidebar-icon {{ request()->routeIs('pomodoro') ? 'active' : '' }}"
                style="background-image: url('https://img.icons8.com/fluency/100/apple.png');">
            </div>
        </a>

        <!-- Estudos (NOVO) -->
        
    </div>
</div>
