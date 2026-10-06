<x-agent.layout>
    @push('styles')
    <style>
        
        .filters-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-select {
            background-color: #1a1d21;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            min-width: 200px;
        }
        .filter-select:focus {
            background-color: #1a1d21;
            border-color: #ef4444;
            color: #fff;
            box-shadow: none;
        }
        .search-input-group {
            display: flex;
            background-color: #1a1d21;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            overflow: hidden;
            min-width: 300px;
            flex-grow: 1;
            max-width: 400px;
        }
        .search-input-group input {
            background: transparent;
            border: none;
            color: #fff;
            padding: 0.6rem 1rem;
            width: 100%;
        }
        .search-input-group input:focus {
            outline: none;
            box-shadow: none;
        }
        .search-input-group .search-icon {
            padding: 0.6rem 1rem;
            color: rgba(255,255,255,0.5);
        }
        .btn-search {
            background-color: #7f1d1d;
            border: 1px solid #991b1b;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-search:hover {
            background-color: #991b1b;
            color: white;
        }

        .directory-table-container {
            background-color: transparent;
        }
        .directory-table {
            width: 100%;
            color: #fff;
            border-collapse: separate;
            border-spacing: 0;
        }
        .directory-table th {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.5px;
        }
        .directory-table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            vertical-align: middle;
        }
        .directory-table tr:hover td {
            background-color: rgba(255,255,255,0.02);
        }

        .agent-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            color: white;
            flex-shrink: 0;
        }
        
        /* Avatar colors based on user role or initials */
        .bg-avatar-1 { background-color: #2563eb; } /* Blue */
        .bg-avatar-2 { background-color: #f59e0b; } /* Orange */
        .bg-avatar-3 { background-color: #db2777; } /* Pink */
        .bg-avatar-4 { background-color: #0891b2; } /* Cyan */
        .bg-avatar-5 { background-color: #8b5cf6; } /* Purple */

        .agent-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: #fff;
            margin-bottom: 0.1rem;
        }
        .agent-meta {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
        }
        .agent-meta span {
            color: #3b82f6; /* Blue email */
        }

        .dept-badge {
            background-color: #1e293b;
            border: 1px solid rgba(255,255,255,0.1);
            color: #cbd5e1;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .role-admin { background-color: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .role-vigitec { background-color: #475569; color: #fff; border: 1px solid #64748b; }
        .role-agent { background-color: rgba(59, 130, 246, 0.2); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); }

        .status-badge {
            background-color: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .status-inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .tickets-count {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .tickets-open {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.15rem;
        }
        .dot-red {
            width: 6px;
            height: 6px;
            background-color: #ef4444;
            border-radius: 50%;
            display: inline-block;
        }
        
        .last-access {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .action-btn {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.7);
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .action-btn:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            border-color: rgba(255,255,255,0.4);
        }
    </style>
    @endpush

    <!-- Header Premium Componente -->
    <div class="tickets-shell">
        <x-agent.page-header title="Directorio de Agentes">
            {{ $totalAgentes }} agentes encontrados
        </x-agent.page-header>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('agent.directory') }}" class="filters-container">
        <select name="did" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos los departamentos</option>
            @foreach($departamentos as $depto)
                <option value="{{ $depto->id }}" {{ request('did') == $depto->id ? 'selected' : '' }}>{{ $depto->name }}</option>
            @endforeach
        </select>

        <div class="search-input-group">
            <span class="search-icon"><i class="bi bi-search"></i></span>
            <input type="text" name="q" placeholder="Buscar por nombre, email o username..." value="{{ request('q') }}">
        </div>

        <button type="submit" class="btn-search">
            <i class="bi bi-search me-1"></i> Buscar
        </button>
    </form>

    <!-- Table -->
    <div class="directory-table-container">
        <table class="directory-table">
            <thead>
                <tr>
                    <th>AGENTE &uarr;</th>
                    <th>DEPARTAMENTO</th>
                    <th>ROL</th>
                    <th>ESTADO</th>
                    <th>TICKETS</th>
                    <th>ÚLTIMO ACCESO</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($agentes as $agente)
                    @php
                        // Calculate initials and avatar color
                        $initials = strtoupper(substr($agente->firstname, 0, 1) . substr($agente->lastname, 0, 1));
                        $colorIndex = ($agente->id % 5) + 1; // 1 to 5
                        
                        // Role badge logic
                        $roleName = $agente->role ?: 'Agente';
                        $roleClass = 'role-agent';
                        if (strtolower($roleName) == 'admin' || strtolower($roleName) == 'administrador') {
                            $roleClass = 'role-admin';
                        } elseif (stripos($roleName, 'vigitec') !== false) {
                            $roleClass = 'role-vigitec';
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="agent-avatar bg-avatar-{{ $colorIndex }}">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="agent-name">{{ $agente->firstname }} {{ $agente->lastname }}</div>
                                    <div class="agent-meta">
                                        {{ '@' . $agente->username }} • <span>{{ $agente->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($agente->department)
                                <div class="dept-badge">
                                    <i class="bi bi-building"></i> {{ $agente->department->name }}
                                </div>
                            @else
                                <span class="text-muted small">Sin departamento</span>
                            @endif
                        </td>
                        <td>
                            <span class="role-badge {{ $roleClass }}">{{ strtoupper($roleName) }}</span>
                        </td>
                        <td>
                            @if($agente->is_active)
                                <span class="status-badge"><i class="bi bi-check-circle-fill"></i> Activo</span>
                            @else
                                <span class="status-badge status-inactive"><i class="bi bi-x-circle-fill"></i> Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="tickets-count">{{ $agente->total_asignados ?? 0 }} total</div>
                            <div class="tickets-open">
                                @if(($agente->abiertos ?? 0) > 0)
                                    <span class="dot-red"></span> <span class="text-danger">{{ $agente->abiertos }} abierto(s)</span>
                                @else
                                    Sin abiertos
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="last-access">
                                <i class="bi bi-clock"></i> 
                                {{ $agente->last_login ? $agente->last_login->format('d/m/Y h:i A') : 'Nunca' }}
                            </div>
                        </td>
                        <td>
                            @if($agente->email)
                            <a href="mailto:{{ $agente->email }}" class="action-btn" title="Enviar correo">
                                <i class="bi bi-envelope"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people d-block mb-3" style="font-size: 2rem;"></i>
                            No se encontraron agentes que coincidan con la búsqueda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($agentes->hasPages())
    <div class="mt-4 pt-3 border-top border-secondary" style="border-color: rgba(255,255,255,0.1) !important;">
        {{ $agentes->links('pagination::bootstrap-5') }}
    </div>
    @endif
</x-agent.layout>