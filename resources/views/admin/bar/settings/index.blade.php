@extends('layouts.admin')

@section('title', __('bar.settings.title') . ' - ' . config('app.name'))
@section('page_title', __('bar.settings.title'))

@push('styles')
    {{-- CSS styles will be loaded via main layout --}}
@endpush

@push('scripts')
    @vite('resources/js/admin/bar/settings.js')
@endpush

@section('content')
<div class="settings-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('bar.settings.title') }}</h1>
                <p class="page-subtitle">{{ __('bar.settings.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary reset-settings-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('bar.settings.reset_defaults') }}
                </button>
                <button type="button" class="btn btn-primary save-settings-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('bar.settings.save_settings') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="settings-content">
        <form class="settings-form" id="settings-form">
            <!-- Drink Portion Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('bar.settings.drink_portions') }}</h3>
                    <p class="section-description">{{ __('bar.settings.drink_portions_description') }}</p>
                </div>
                
                <div class="settings-cards">
                    <!-- Standard Pour Sizes -->
                    <div class="setting-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('bar.settings.standard_pour_sizes') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="standard-shot" class="form-label">{{ __('bar.settings.standard_shot_size') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="standard-shot" name="standard_shot_size" class="form-input" 
                                               min="15" max="50" step="0.5" value="25">
                                        <span class="input-suffix">ml</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.shot_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="double-shot" class="form-label">{{ __('bar.settings.double_shot_size') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="double-shot" name="double_shot_size" class="form-input" 
                                               min="30" max="100" step="0.5" value="50">
                                        <span class="input-suffix">ml</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.double_shot_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="wine-pour" class="form-label">{{ __('bar.settings.wine_pour_size') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="wine-pour" name="wine_pour_size" class="form-input" 
                                               min="100" max="200" step="5" value="150">
                                        <span class="input-suffix">ml</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.wine_pour_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="beer-pour" class="form-label">{{ __('bar.settings.beer_pour_size') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="beer-pour" name="beer_pour_size" class="form-input" 
                                               min="200" max="500" step="10" value="330">
                                        <span class="input-suffix">ml</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.beer_pour_hint') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Container Conversion Rates -->
                    <div class="setting-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('bar.settings.container_conversions') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="conversion-list" id="conversion-list">
                                <!-- Beer Conversions -->
                                <div class="conversion-item">
                                    <div class="conversion-header">
                                        <h5 class="conversion-title">{{ __('bar.settings.beer_conversions') }}</h5>
                                    </div>
                                    <div class="conversion-grid">
                                        <div class="form-group">
                                            <label for="pint-to-glasses" class="form-label">{{ __('bar.settings.pint_to_glasses') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.pint') }} =</span>
                                                <input type="number" id="pint-to-glasses" name="pint_to_glasses" class="form-input" 
                                                       min="50" max="100" step="1" value="80">
                                                <span class="conversion-unit">{{ __('bar.settings.glasses') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.pint_conversion_hint') }}</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="gallon-to-pints" class="form-label">{{ __('bar.settings.gallon_to_pints') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.gallon') }} =</span>
                                                <input type="number" id="gallon-to-pints" name="gallon_to_pints" class="form-input" 
                                                       min="6" max="10" step="0.5" value="8">
                                                <span class="conversion-unit">{{ __('bar.settings.pints') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.gallon_conversion_hint') }}</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="keg-to-pints" class="form-label">{{ __('bar.settings.keg_to_pints') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.keg') }} =</span>
                                                <input type="number" id="keg-to-pints" name="keg_to_pints" class="form-input" 
                                                       min="80" max="160" step="5" value="124">
                                                <span class="conversion-unit">{{ __('bar.settings.pints') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.keg_conversion_hint') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Spirit Conversions -->
                                <div class="conversion-item">
                                    <div class="conversion-header">
                                        <h5 class="conversion-title">{{ __('bar.settings.spirit_conversions') }}</h5>
                                    </div>
                                    <div class="conversion-grid">
                                        <div class="form-group">
                                            <label for="bottle-to-singles" class="form-label">{{ __('bar.settings.bottle_to_singles') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.bottle_750ml') }} =</span>
                                                <input type="number" id="bottle-to-singles" name="bottle_to_singles" class="form-input" 
                                                       min="25" max="35" step="1" value="30">
                                                <span class="conversion-unit">{{ __('bar.settings.single_shots') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.bottle_single_hint') }}</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="bottle-to-doubles" class="form-label">{{ __('bar.settings.bottle_to_doubles') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.bottle_750ml') }} =</span>
                                                <input type="number" id="bottle-to-doubles" name="bottle_to_doubles" class="form-input" 
                                                       min="12" max="18" step="1" value="15">
                                                <span class="conversion-unit">{{ __('bar.settings.double_shots') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.bottle_double_hint') }}</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="liter-to-singles" class="form-label">{{ __('bar.settings.liter_to_singles') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.liter') }} =</span>
                                                <input type="number" id="liter-to-singles" name="liter_to_singles" class="form-input" 
                                                       min="35" max="45" step="1" value="40">
                                                <span class="conversion-unit">{{ __('bar.settings.single_shots') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.liter_conversion_hint') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Wine Conversions -->
                                <div class="conversion-item">
                                    <div class="conversion-header">
                                        <h5 class="conversion-title">{{ __('bar.settings.wine_conversions') }}</h5>
                                    </div>
                                    <div class="conversion-grid">
                                        <div class="form-group">
                                            <label for="wine-bottle-to-glasses" class="form-label">{{ __('bar.settings.wine_bottle_to_glasses') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.wine_bottle') }} =</span>
                                                <input type="number" id="wine-bottle-to-glasses" name="wine_bottle_to_glasses" class="form-input" 
                                                       min="4" max="8" step="1" value="5">
                                                <span class="conversion-unit">{{ __('bar.settings.wine_glasses') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.wine_bottle_hint') }}</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="wine-case-to-bottles" class="form-label">{{ __('bar.settings.case_to_bottles') }}</label>
                                            <div class="conversion-input">
                                                <span class="conversion-label">1 {{ __('bar.settings.case') }} =</span>
                                                <input type="number" id="wine-case-to-bottles" name="wine_case_to_bottles" class="form-input" 
                                                       min="6" max="24" step="1" value="12">
                                                <span class="conversion-unit">{{ __('bar.settings.bottles') }}</span>
                                            </div>
                                            <small class="form-hint">{{ __('bar.settings.case_conversion_hint') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Custom Conversion -->
                            <div class="add-conversion-section">
                                <button type="button" class="btn btn-secondary add-conversion-btn">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ __('bar.settings.add_custom_conversion') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Alert Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('bar.settings.inventory_alerts') }}</h3>
                    <p class="section-description">{{ __('bar.settings.inventory_alerts_description') }}</p>
                </div>
                
                <div class="settings-cards">
                    <!-- Low Stock Thresholds -->
                    <div class="setting-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('bar.settings.low_stock_thresholds') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="beer-threshold" class="form-label">{{ __('bar.settings.beer_threshold') }}</label>
                                    <div class="threshold-input">
                                        <input type="number" id="beer-threshold" name="beer_threshold" class="form-input" 
                                               min="1" max="20" step="1" value="3">
                                        <span class="threshold-unit">{{ __('bar.settings.pints_remaining') }}</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.beer_threshold_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="spirits-threshold" class="form-label">{{ __('bar.settings.spirits_threshold') }}</label>
                                    <div class="threshold-input">
                                        <input type="number" id="spirits-threshold" name="spirits_threshold" class="form-input" 
                                               min="5" max="50" step="1" value="10">
                                        <span class="threshold-unit">{{ __('bar.settings.shots_remaining') }}</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.spirits_threshold_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="wine-threshold" class="form-label">{{ __('bar.settings.wine_threshold') }}</label>
                                    <div class="threshold-input">
                                        <input type="number" id="wine-threshold" name="wine_threshold" class="form-input" 
                                               min="1" max="10" step="1" value="2">
                                        <span class="threshold-unit">{{ __('bar.settings.glasses_remaining') }}</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.wine_threshold_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="mixers-threshold" class="form-label">{{ __('bar.settings.mixers_threshold') }}</label>
                                    <div class="threshold-input">
                                        <input type="number" id="mixers-threshold" name="mixers_threshold" class="form-input" 
                                               min="5" max="50" step="1" value="20">
                                        <span class="threshold-unit">{{ __('bar.settings.servings_remaining') }}</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.mixers_threshold_hint') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Settings -->
                    <div class="setting-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('bar.settings.alert_preferences') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">{{ __('bar.settings.alert_methods') }}</label>
                                    <div class="checkbox-group">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="alert_methods[]" value="dashboard" checked>
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.dashboard_notifications') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="alert_methods[]" value="email" checked>
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.email_notifications') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="alert_methods[]" value="sms">
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.sms_notifications') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alert-frequency" class="form-label">{{ __('bar.settings.alert_frequency') }}</label>
                                    <select id="alert-frequency" name="alert_frequency" class="form-select">
                                        <option value="immediate">{{ __('bar.settings.immediate') }}</option>
                                        <option value="daily" selected>{{ __('bar.settings.daily_summary') }}</option>
                                        <option value="weekly">{{ __('bar.settings.weekly_summary') }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="alert-recipients" class="form-label">{{ __('bar.settings.alert_recipients') }}</label>
                                    <textarea id="alert-recipients" name="alert_recipients" class="form-textarea" rows="3"
                                              placeholder="{{ __('bar.settings.alert_recipients_placeholder') }}"></textarea>
                                    <small class="form-hint">{{ __('bar.settings.alert_recipients_hint') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Bar Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('bar.settings.general_settings') }}</h3>
                    <p class="section-description">{{ __('bar.settings.general_settings_description') }}</p>
                </div>
                
                <div class="settings-cards">
                    <!-- Operating Settings -->
                    <div class="setting-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('bar.settings.operating_settings') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="bar-name" class="form-label">{{ __('bar.settings.bar_name') }}</label>
                                    <input type="text" id="bar-name" name="bar_name" class="form-input" 
                                           placeholder="{{ __('bar.settings.bar_name_placeholder') }}" value="Main Bar">
                                </div>

                                <div class="form-group">
                                    <label for="default-markup" class="form-label">{{ __('bar.settings.default_markup') }}</label>
                                    <div class="input-with-suffix">
                                        <input type="number" id="default-markup" name="default_markup" class="form-input" 
                                               min="50" max="300" step="5" value="150">
                                        <span class="input-suffix">%</span>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.markup_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="last-call-time" class="form-label">{{ __('bar.settings.last_call_time') }}</label>
                                    <input type="time" id="last-call-time" name="last_call_time" class="form-input" value="23:30">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('bar.settings.features') }}</label>
                                    <div class="checkbox-group">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="features[]" value="happy_hour" checked>
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.happy_hour_enabled') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="features[]" value="age_verification" checked>
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.age_verification') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="features[]" value="stock_rotation" checked>
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.stock_rotation') }}
                                        </label>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="features[]" value="waste_tracking">
                                            <span class="checkmark"></span>
                                            {{ __('bar.settings.waste_tracking') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- POS Integration -->
                    <div class="setting-card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('bar.settings.pos_integration') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">{{ __('bar.settings.auto_inventory_update') }}</label>
                                    <div class="toggle-group">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="auto_inventory_update" value="1" checked>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">{{ __('bar.settings.enable_auto_update') }}</span>
                                        </label>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.auto_update_hint') }}</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('bar.settings.real_time_sync') }}</label>
                                    <div class="toggle-group">
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="real_time_sync" value="1">
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-label">{{ __('bar.settings.enable_real_time') }}</span>
                                        </label>
                                    </div>
                                    <small class="form-hint">{{ __('bar.settings.real_time_hint') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion Calculator -->
            <div class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">{{ __('bar.settings.conversion_calculator') }}</h3>
                    <p class="section-description">{{ __('bar.settings.calculator_description') }}</p>
                </div>
                
                <div class="calculator-card">
                    <div class="calculator-header">
                        <h4 class="card-title">{{ __('bar.settings.quick_calculator') }}</h4>
                    </div>
                    <div class="calculator-body">
                        <div class="calculator-grid">
                            <div class="calc-input-group">
                                <label for="calc-quantity" class="calc-label">{{ __('bar.settings.quantity') }}</label>
                                <input type="number" id="calc-quantity" class="calc-input" min="1" step="0.1" value="1">
                            </div>
                            
                            <div class="calc-input-group">
                                <label for="calc-from-unit" class="calc-label">{{ __('bar.settings.from_unit') }}</label>
                                <select id="calc-from-unit" class="calc-select">
                                    <option value="gallon">{{ __('bar.settings.gallon') }}</option>
                                    <option value="pint">{{ __('bar.settings.pint') }}</option>
                                    <option value="bottle_750ml">{{ __('bar.settings.bottle_750ml') }}</option>
                                    <option value="liter">{{ __('bar.settings.liter') }}</option>
                                    <option value="keg">{{ __('bar.settings.keg') }}</option>
                                    <option value="case">{{ __('bar.settings.case') }}</option>
                                </select>
                            </div>
                            
                            <div class="calc-input-group">
                                <label for="calc-to-unit" class="calc-label">{{ __('bar.settings.to_unit') }}</label>
                                <select id="calc-to-unit" class="calc-select">
                                    <option value="glasses" selected>{{ __('bar.settings.glasses') }}</option>
                                    <option value="shots">{{ __('bar.settings.shots') }}</option>
                                    <option value="servings">{{ __('bar.settings.servings') }}</option>
                                    <option value="pints">{{ __('bar.settings.pints') }}</option>
                                    <option value="bottles">{{ __('bar.settings.bottles') }}</option>
                                </select>
                            </div>
                            
                            <div class="calc-result-group">
                                <label class="calc-label">{{ __('bar.settings.result') }}</label>
                                <div class="calc-result" id="calc-result">
                                    <span class="result-value" id="result-value">80</span>
                                    <span class="result-unit" id="result-unit">glasses</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-primary calculate-btn">
                            {{ __('bar.settings.calculate') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Custom Conversion Modal -->
<div class="custom-conversion-modal" id="custom-conversion-modal" style="display: none;" role="dialog" aria-labelledby="conversion-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="conversion-modal-title" class="modal-title">{{ __('bar.settings.add_custom_conversion') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('common.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="custom-conversion-form" id="custom-conversion-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="conversion-name" class="form-label required">{{ __('bar.settings.conversion_name') }}</label>
                        <input type="text" id="conversion-name" name="conversion_name" class="form-input" required
                               placeholder="{{ __('bar.settings.conversion_name_placeholder') }}">
                    </div>

                    <div class="form-group">
                        <label for="from-container" class="form-label required">{{ __('bar.settings.from_container') }}</label>
                        <input type="text" id="from-container" name="from_container" class="form-input" required
                               placeholder="{{ __('bar.settings.from_container_placeholder') }}">
                    </div>

                    <div class="form-group">
                        <label for="to-unit" class="form-label required">{{ __('bar.settings.to_unit_label') }}</label>
                        <input type="text" id="to-unit" name="to_unit" class="form-input" required
                               placeholder="{{ __('bar.settings.to_unit_placeholder') }}">
                    </div>

                    <div class="form-group">
                        <label for="conversion-rate" class="form-label required">{{ __('bar.settings.conversion_rate') }}</label>
                        <input type="number" id="conversion-rate" name="conversion_rate" class="form-input" required
                               min="1" step="0.1" placeholder="25">
                        <small class="form-hint">{{ __('bar.settings.conversion_rate_hint') }}</small>
                    </div>

                    <div class="form-group full-width">
                        <label for="conversion-notes" class="form-label">{{ __('common.notes') }}</label>
                        <textarea id="conversion-notes" name="conversion_notes" class="form-textarea" rows="2"
                                  placeholder="{{ __('bar.settings.conversion_notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-btn">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" form="custom-conversion-form" class="btn btn-primary save-btn">
                {{ __('bar.settings.add_conversion') }}
            </button>
        </div>
    </div>
</div>
@endsection
