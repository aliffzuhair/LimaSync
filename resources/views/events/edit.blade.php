@extends('layouts.app')

@section('title', 'Edit Event - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Event</h1>
    <a href="{{ route('events.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('events.update', $event) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_name" class="form-label">Event Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('event_name') is-invalid @enderror" 
                           id="event_name" name="event_name" value="{{ old('event_name', $event->event_name) }}" required>
                    @error('event_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                    <select class="form-control @error('client_id') is-invalid @enderror" 
                            id="client_id" name="client_id" required>
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $event->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="event_type" class="form-label">Event Type <span class="text-danger">*</span></label>
                    <select class="form-control @error('event_type') is-invalid @enderror" 
                            id="event_type" name="event_type" required>
                        <option value="">Select Type</option>
                        <option value="Conference" {{ old('event_type', $event->event_type) == 'Conference' ? 'selected' : '' }}>Conference</option>
                        <option value="Workshop" {{ old('event_type', $event->event_type) == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="Seminar" {{ old('event_type', $event->event_type) == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="Exhibition" {{ old('event_type', $event->event_type) == 'Exhibition' ? 'selected' : '' }}>Exhibition</option>
                        <option value="Gala Dinner" {{ old('event_type', $event->event_type) == 'Gala Dinner' ? 'selected' : '' }}>Gala Dinner</option>
                        <option value="Team Building" {{ old('event_type', $event->event_type) == 'Team Building' ? 'selected' : '' }}>Team Building</option>
                        <option value="Corporate Meeting" {{ old('event_type', $event->event_type) == 'Corporate Meeting' ? 'selected' : '' }}>Corporate Meeting</option>
                        <option value="Other" {{ old('event_type', $event->event_type) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('event_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" class="form-control @error('venue') is-invalid @enderror" 
                           id="venue" name="venue" value="{{ old('venue', $event->venue) }}">
                    @error('venue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                           id="start_date" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                           id="end_date" name="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                           id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}">
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                           id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}">
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="estimated_attendees" class="form-label">Estimated Attendees</label>
                    <input type="number" class="form-control @error('estimated_attendees') is-invalid @enderror" 
                           id="estimated_attendees" name="estimated_attendees" value="{{ old('estimated_attendees', $event->estimated_attendees) }}" min="0">
                    @error('estimated_attendees')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="budget" class="form-label">Budget (RM)</label>
                    <input type="number" step="0.01" class="form-control @error('budget') is-invalid @enderror" 
                           id="budget" name="budget" value="{{ old('budget', $event->budget) }}" min="0">
                    @error('budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="planning" {{ old('status', $event->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="in_progress" {{ old('status', $event->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="2">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="2">{{ old('notes', $event->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Event
            </button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection