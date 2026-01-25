@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">System Settings</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Tab Navigation --}}
                            <ul class="nav nav-tabs mb-3" id="settingTabs" role="tablist">
                                @foreach($settings as $group => $groupSettings)
                                    <li class="nav-item">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="{{ $group }}-tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#{{ $group }}"
                                                type="button"
                                                role="tab">
                                            {{ ucfirst($group) }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="settingTabsContent">
                                @foreach($settings as $group => $groupSettings)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                         id="{{ $group }}"
                                         role="tabpanel">

                                        {{-- Hiển thị input cho từng setting trong group --}}
                                        @foreach($groupSettings as $setting)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">{{ $setting->label }}</label>

                                                @if($setting->type === 'text' || $setting->type === 'email')
                                                    <input type="{{ $setting->type }}" class="form-control"
                                                           name="{{ $setting->key }}"
                                                           value="{{ $setting->value }}">

                                                @elseif($setting->type === 'textarea')
                                                    <textarea class="form-control" name="{{ $setting->key }}"
                                                              rows="3">{{ $setting->value }}</textarea>

                                                @elseif($setting->type === 'select')
                                                    @php
                                                        $options = json_decode($setting->options, true, 512, JSON_THROW_ON_ERROR) ?? [];
                                                    @endphp
                                                    <select class="form-select" name="{{ $setting->key }}">
                                                        @foreach($options as $optValue => $optLabel)
                                                            <option
                                                                value="{{ $optValue }}" {{ $setting->value === $optValue ? 'selected' : '' }}>
                                                                {{ $optLabel }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @if($setting->description)
                                                    <small class="text-muted">{{ $setting->description }}</small>
                                                @endif
                                            </div>
                                        @endforeach

                                        @if($group === 'general')
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Website Logo</label>
                                                <input type="file" class="form-control" name="site_logo"
                                                       accept="image/*">
                                                @php
                                                    // Helper setting() của bạn đã được định nghĩa
                                                    $logoPath = setting('site_logo');
                                                @endphp
                                                @if($logoPath)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/' . $logoPath) }}"
                                                             alt="Current Logo"
                                                             style="height: 60px; border: 1px solid #eee; padding: 5px; border-radius: 4px;">
                                                    </div>
                                                @endif
                                                <small class="text-muted">Recommended size: 200x50px. Max size:
                                                    2MB.</small>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
