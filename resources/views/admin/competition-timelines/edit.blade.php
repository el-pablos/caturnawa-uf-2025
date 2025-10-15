@extends('layouts.admin')

@section('title', 'Edit Competition Timeline')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Timeline Event</h1>
        <a href="{{ route('admin.competition-timelines.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.competition-timelines.update', $competitionTimeline) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="competition_id" class="form-label">Competition <span class="text-danger">*</span></label>
                    <select class="form-select @error('competition_id') is-invalid @enderror" 
                            id="competition_id" name="competition_id" required>
                        <option value="">Select competition...</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}" {{ old('competition_id', $competitionTimeline->competition_id) == $competition->id ? 'selected' : '' }}>
                                {{ $competition->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('competition_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                        <select class="form-select @error('month') is-invalid @enderror" id="month" name="month" required>
                            <option value="">Select month...</option>
                            <option value="January" {{ old('month', $competitionTimeline->month) === 'January' ? 'selected' : '' }}>January</option>
                            <option value="February" {{ old('month', $competitionTimeline->month) === 'February' ? 'selected' : '' }}>February</option>
                            <option value="March" {{ old('month', $competitionTimeline->month) === 'March' ? 'selected' : '' }}>March</option>
                            <option value="April" {{ old('month', $competitionTimeline->month) === 'April' ? 'selected' : '' }}>April</option>
                            <option value="May" {{ old('month', $competitionTimeline->month) === 'May' ? 'selected' : '' }}>May</option>
                            <option value="June" {{ old('month', $competitionTimeline->month) === 'June' ? 'selected' : '' }}>June</option>
                            <option value="July" {{ old('month', $competitionTimeline->month) === 'July' ? 'selected' : '' }}>July</option>
                            <option value="August" {{ old('month', $competitionTimeline->month) === 'August' ? 'selected' : '' }}>August</option>
                            <option value="September" {{ old('month', $competitionTimeline->month) === 'September' ? 'selected' : '' }}>September</option>
                            <option value="October" {{ old('month', $competitionTimeline->month) === 'October' ? 'selected' : '' }}>October</option>
                            <option value="November" {{ old('month', $competitionTimeline->month) === 'November' ? 'selected' : '' }}>November</option>
                            <option value="December" {{ old('month', $competitionTimeline->month) === 'December' ? 'selected' : '' }}>December</option>
                        </select>
                        @error('month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="day" class="form-label">Day <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('day') is-invalid @enderror" 
                               id="day" name="day" value="{{ old('day', $competitionTimeline->day) }}" 
                               min="1" max="31" required>
                        @error('day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" 
                               id="year" name="year" value="{{ old('year', $competitionTimeline->year) }}" 
                               min="2024" max="2030" required>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $competitionTimeline->title) }}" 
                           placeholder="e.g., Registration Opens, Submission Deadline" required maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Display Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                           id="order" name="order" value="{{ old('order', $competitionTimeline->order) }}" 
                           min="0" required>
                    <small class="form-text text-muted">Lower numbers appear first in the timeline</small>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                           {{ old('is_active', $competitionTimeline->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (visible on public timeline page)
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Timeline Event
                    </button>
                    <a href="{{ route('admin.competition-timelines.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

