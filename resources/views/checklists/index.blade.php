@extends('layouts.app')

@section('title', 'Checklist - ' . $event->event_name . ' - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $event->event_name }}</h1>
        <p class="mb-4">
            <i class="fas fa-users"></i> {{ $event->client->company_name ?? 'N/A' }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M Y') }}
        </p>
    </div>
    <div>
        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Event
        </a>
    </div>
</div>

<!-- Progress Summary -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Event Progress</h5>
                        <small class="text-muted">{{ $event->progress_percentage }}% Complete</small>
                    </div>
                    <div class="col-md-6">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-{{ 
                                $event->progress_percentage == 100 ? 'success' : 
                                ($event->progress_percentage >= 50 ? 'warning' : 'danger') 
                            }}" 
                                 role="progressbar" 
                                 style="width: {{ $event->progress_percentage }}%">
                                {{ $event->progress_percentage }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3 text-center">
                        <span class="badge bg-{{ 
                            $event->status == 'completed' ? 'success' : 
                            ($event->status == 'in_progress' ? 'warning' : 
                            ($event->status == 'cancelled' ? 'danger' : 'secondary')) 
                        }}" style="font-size: 1rem; padding: 8px 20px;">
                            <i class="fas fa-{{ $event->status == 'completed' ? 'check-circle' : 'clock' }}"></i>
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <div class="col-md-3 text-center">
                        <span class="text-muted">
                            <i class="fas fa-check-circle text-success"></i>
                            {{ $event->eventChecklists->where('status', 'completed')->count() }} / 
                            {{ $event->eventChecklists->where('status', '!=', 'not_applicable')->count() }} Completed
                        </span>
                    </div>
                    <div class="col-md-3 text-center">
                        <span class="text-muted">
                            <i class="fas fa-clock text-warning"></i>
                            {{ $event->eventChecklists->where('status', 'in_progress')->count() }} In Progress
                        </span>
                    </div>
                    <div class="col-md-3 text-center">
                        <span class="text-muted">
                            <i class="fas fa-hourglass-start text-secondary"></i>
                            {{ $event->eventChecklists->where('status', 'pending')->count() }} Pending
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checklists by Phase -->
<!-- Checklists by Phase -->
@foreach($checklists as $phaseName => $phaseItems)
    @php
        $isUnlocked = $event->isPhaseUnlocked($phaseName);
        $phaseProgress = $event->getPhaseProgress($phaseName);
        $previousPhase = match($phaseName) {
            'during_event' => 'Pre-Event',
            'post_event' => 'During-Event',
            default => null,
        };
    @endphp

    <div class="card mb-4 {{ !$isUnlocked ? 'phase-locked' : '' }}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-{{ 
                    $phaseName == 'pre_event' ? 'clipboard-list' : 
                    ($phaseName == 'during_event' ? 'play' : 'flag-checkered') 
                }}"></i>
                {{ ucfirst(str_replace('_', ' ', $phaseName)) }}
                <span class="badge bg-light text-dark ms-2">
                    {{ $phaseProgress['completed'] }}/{{ $phaseProgress['total'] }}
                </span>

                {{-- Phase status badge --}}
                @if($isUnlocked)
                    <span class="badge bg-success ms-2">
                        <i class="fas fa-unlock"></i> Unlocked
                    </span>
                @else
                    <span class="badge bg-danger ms-2">
                        <i class="fas fa-lock"></i> Locked
                    </span>
                @endif
            </div>

            {{-- Phase progress bar --}}
            <div style="width: 200px;">
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-{{ 
                        $phaseProgress['percentage'] == 100 ? 'success' : 
                        ($phaseProgress['percentage'] >= 50 ? 'warning' : 'info') 
                    }}" 
                         role="progressbar" 
                         style="width: {{ $phaseProgress['percentage'] }}%">
                        {{ $phaseProgress['percentage'] }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- Locked notice --}}
            @if(!$isUnlocked)
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-lock"></i>
                    <strong>This phase is locked.</strong>
                    Complete all items in <strong>{{ $previousPhase }}</strong> to unlock this phase.
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Item Name</th>
                            <th style="width: 30%;">Description</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 15%;">Completed By</th>
                            <th style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($phaseItems as $item)
                        <tr class="{{ !$isUnlocked ? 'text-muted' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $item->checklist->item_name }}
                                @if(!$item->checklist->is_required)
                                    <span class="badge bg-light text-muted ms-1">Optional</span>
                                @endif
                            </td>
                            <td>{{ $item->checklist->description ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $item->status == 'completed' ? 'success' : 
                                    ($item->status == 'in_progress' ? 'warning' : 
                                    ($item->status == 'not_applicable' ? 'secondary' : 'secondary')) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($item->completed_by)
                                    {{ $item->completedBy->full_name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">{{ $item->completed_at ? $item->completed_at->format('d M Y H:i') : '' }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            onclick="updateStatus({{ $item->id }}, 'completed')" 
                                            title="Mark Complete"
                                            {{ !$isUnlocked ? 'disabled' : '' }}>
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning" 
                                            onclick="updateStatus({{ $item->id }}, 'in_progress')" 
                                            title="In Progress"
                                            {{ !$isUnlocked ? 'disabled' : '' }}>
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            onclick="updateStatus({{ $item->id }}, 'pending')" 
                                            title="Pending"
                                            {{ !$isUnlocked ? 'disabled' : '' }}>
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            onclick="updateStatus({{ $item->id }}, 'not_applicable')" 
                                            title="Not Applicable"
                                            {{ !$isUnlocked ? 'disabled' : '' }}>
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach

<!-- Update Status Form (Hidden) -->
<form id="update-status-form" action="" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="status-input">
</form>

@push('scripts')
<script>
function updateStatus(itemId, status) {
    if (confirm('Update this checklist item to "' + status.replace('_', ' ') + '"?')) {
        const form = document.getElementById('update-status-form');
        const url = '{{ route("checklists.update", ":id") }}'.replace(':id', itemId);
        form.action = url;
        document.getElementById('status-input').value = status;
        form.submit();
    }
}
</script>
@endpush
@endsection