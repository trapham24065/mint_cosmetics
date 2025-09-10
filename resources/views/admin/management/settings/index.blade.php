@extends('admin.layouts.app')
@section('content')
    <div class="container-xxl">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Application Settings</h4>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($settings as $group => $groupSettings)
                            <li class="nav-item">
                                <a class="nav-link @if($loop->first) active @endif" data-bs-toggle="tab"
                                   href="#{{ $group }}" role="tab">{{ ucfirst($group) }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content p-3">
                        @foreach($settings as $group => $groupSettings)
                            <div class="tab-pane @if($loop->first) active @endif" id="{{ $group }}" role="tabpanel">
                                @foreach($groupSettings as $setting)
                                    <div class="mb-3">
                                        <label for="{{ $setting->key }}"
                                               class="form-label">{{ $setting->label }}</label>

                                        @if($setting->type === 'text' || $setting->type === 'email' || $setting->type === 'number')
                                            <input type="{{ $setting->type }}" name="{{ $setting->key }}"
                                                   id="{{ $setting->key }}" class="form-control"
                                                   value="{{ old($setting->key, $setting->value) }}">
                                        @elseif($setting->type === 'textarea')
                                            <textarea name="{{ $setting->key }}" id="{{ $setting->key }}"
                                                      class="form-control"
                                                      rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                                        @elseif($setting->type === 'select' && is_array(json_decode($setting->options, true)))
                                            <select name="{{ $setting->key }}" id="{{ $setting->key }}"
                                                    class="form-select">
                                                @foreach(json_decode($setting->options, true) as $optionValue => $optionLabel)
                                                    <option
                                                        value="{{ $optionValue }}" @selected(old($setting->key, $setting->value) == $optionValue)>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif

                                        @if($setting->description)
                                            <small class="form-text text-muted">{{ $setting->description }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
