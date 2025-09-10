@extends('layouts.admin')

@section('title', __('menu.dish_cost.title') . ' - ' . config('app.name'))
@section('page_title', __('menu.dish_cost.title'))

@push('styles')
    @vite('resources/css/admin/menu-dish-cost.css')
@endpush

@push('scripts')
    @vite('resources/js/admin/menu-dish-cost.js')
@endpush

@section('content')
<div class="dish-cost-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title-section">
                <h1 class="page-title">{{ __('menu.dish_cost.title') }}</h1>
                <p class="page-subtitle">{{ __('menu.dish_cost.subtitle') }}</p>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-secondary load-dish-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    {{ __('menu.dish_cost.load_existing_dish') }}
                </button>
                <button type="button" class="btn btn-secondary save-template-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    {{ __('menu.dish_cost.save_as_template') }}
                </button>
                <button type="button" class="btn btn-primary new-calculation-btn">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('menu.dish_cost.new_calculation') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dish-cost-content">
        <div class="cost-calculation-layout">
            <!-- Left Panel - Cost Inputs -->
            <div class="cost-inputs-panel">
                <!-- Dish Information -->
                <div class="calculation-section">
                    <div class="section-header">
                        <h2 class="section-title">{{ __('menu.dish_cost.dish_information') }}</h2>
                        <p class="section-subtitle">{{ __('menu.dish_cost.dish_info_subtitle') }}</p>
                    </div>
                    <div class="section-content">
                        <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                            <div class="form-group md:col-span-2">
                                <label for="dish-name" class="form-label required">
                                    {{ __('menu.dish_cost.dish_name') }}
                                </label>
                                <input type="text" id="dish-name" name="dish_name" class="form-input" required
                                       placeholder="{{ __('menu.dish_cost.dish_name_placeholder') }}">
                            </div>
                            <div class="form-group">
                                <label for="dish-category" class="form-label">
                                    {{ __('menu.dish_cost.category') }}
                                </label>
                                <select id="dish-category" name="category" class="form-select">
                                    <option value="">{{ __('menu.dish_cost.select_category') }}</option>
                                    <option value="appetizers">{{ __('menu.dish_cost.appetizers') }}</option>
                                    <option value="main_courses">{{ __('menu.dish_cost.main_courses') }}</option>
                                    <option value="desserts">{{ __('menu.dish_cost.desserts') }}</option>
                                    <option value="beverages">{{ __('menu.dish_cost.beverages') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="serving-size" class="form-label">
                                    {{ __('menu.dish_cost.serving_size') }}
                                </label>
                                <input type="number" id="serving-size" name="serving_size" class="form-input" 
                                       min="1" step="1" value="1" placeholder="1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ingredients Section -->
                <div class="calculation-section">
                    <div class="section-header">
                        <h2 class="section-title">{{ __('menu.dish_cost.ingredients') }}</h2>
                        <p class="section-subtitle">{{ __('menu.dish_cost.ingredients_subtitle') }}</p>
                        <button type="button" class="btn btn-secondary add-ingredient-btn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('menu.dish_cost.add_ingredient') }}
                        </button>
                    </div>
                    <div class="section-content">
                        <div class="ingredients-table-wrapper">
                            <table class="ingredients-table" role="table">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('menu.dish_cost.ingredient') }}</th>
                                        <th scope="col">{{ __('menu.dish_cost.quantity') }}</th>
                                        <th scope="col">{{ __('menu.dish_cost.unit') }}</th>
                                        <th scope="col">{{ __('menu.dish_cost.cost_per_unit') }}</th>
                                        <th scope="col">{{ __('menu.dish_cost.total') }}</th>
                                        <th scope="col">{{ __('menu.dish_cost.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="ingredients-table-body" id="ingredients-table-body">
                                    <!-- Ingredients will be added here dynamically -->
                                </tbody>
                            </table>
                        </div>
                        <div class="ingredients-empty" id="ingredients-empty">
                            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <h3>{{ __('menu.dish_cost.no_ingredients') }}</h3>
                            <p>{{ __('menu.dish_cost.no_ingredients_description') }}</p>
                            <button type="button" class="btn btn-primary add-ingredient-btn">
                                {{ __('menu.dish_cost.add_first_ingredient') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Overhead Costs Section -->
                <div class="calculation-section">
                    <div class="section-header">
                        <h2 class="section-title">{{ __('menu.dish_cost.overhead_costs') }}</h2>
                        <p class="section-subtitle">{{ __('menu.dish_cost.overhead_subtitle') }}</p>
                    </div>
                    <div class="section-content">
                        <div class="overhead-options">
                            <div class="overhead-option">
                                <label class="overhead-label">
                                    <input type="radio" name="overhead_type" value="percentage" checked>
                                    <span class="radio-indicator"></span>
                                    <span class="radio-text">{{ __('menu.dish_cost.percentage_of_ingredients') }}</span>
                                </label>
                                <div class="overhead-input-group">
                                    <input type="number" id="overhead-percentage" name="overhead_percentage" 
                                           class="form-input overhead-input" min="0" max="100" step="0.1" value="15">
                                    <span class="input-suffix">%</span>
                                </div>
                            </div>
                            <div class="overhead-option">
                                <label class="overhead-label">
                                    <input type="radio" name="overhead_type" value="fixed">
                                    <span class="radio-indicator"></span>
                                    <span class="radio-text">{{ __('menu.dish_cost.fixed_amount') }}</span>
                                </label>
                                <div class="overhead-input-group">
                                    <span class="input-prefix">£</span>
                                    <input type="number" id="overhead-fixed" name="overhead_fixed" 
                                           class="form-input overhead-input" min="0" step="0.01" value="0.00" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="overhead-breakdown">
                            <h4>{{ __('menu.dish_cost.overhead_breakdown') }}</h4>
                            <div class="breakdown-grid">
                                <div class="breakdown-item">
                                    <label>{{ __('menu.dish_cost.labor_cost') }}</label>
                                    <input type="number" class="breakdown-input" min="0" step="0.1" value="8" data-type="labor">
                                    <span class="breakdown-unit">%</span>
                                </div>
                                <div class="breakdown-item">
                                    <label>{{ __('menu.dish_cost.utilities') }}</label>
                                    <input type="number" class="breakdown-input" min="0" step="0.1" value="3" data-type="utilities">
                                    <span class="breakdown-unit">%</span>
                                </div>
                                <div class="breakdown-item">
                                    <label>{{ __('menu.dish_cost.rent_equipment') }}</label>
                                    <input type="number" class="breakdown-input" min="0" step="0.1" value="2" data-type="rent">
                                    <span class="breakdown-unit">%</span>
                                </div>
                                <div class="breakdown-item">
                                    <label>{{ __('menu.dish_cost.other_expenses') }}</label>
                                    <input type="number" class="breakdown-input" min="0" step="0.1" value="2" data-type="other">
                                    <span class="breakdown-unit">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Cost Summary & Pricing -->
            <div class="cost-summary-panel">
                <!-- Cost Summary -->
                <div class="summary-section">
                    <div class="summary-header">
                        <h2 class="summary-title">{{ __('menu.dish_cost.cost_summary') }}</h2>
                    </div>
                    <div class="summary-content">
                        <div class="cost-breakdown">
                            <div class="cost-item">
                                <div class="cost-label">{{ __('menu.dish_cost.ingredients_cost') }}</div>
                                <div class="cost-value" id="ingredients-cost">£0.00</div>
                            </div>
                            <div class="cost-item">
                                <div class="cost-label">{{ __('menu.dish_cost.overhead_cost') }}</div>
                                <div class="cost-value" id="overhead-cost">£0.00</div>
                            </div>
                            <div class="cost-item total-cost">
                                <div class="cost-label">{{ __('menu.dish_cost.total_cost') }}</div>
                                <div class="cost-value" id="total-cost">£0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="summary-section">
                    <div class="summary-header">
                        <h2 class="summary-title">{{ __('menu.dish_cost.pricing') }}</h2>
                    </div>
                    <div class="summary-content">
                        <div class="pricing-controls">
                            <div class="profit-margin-control">
                                <label for="profit-margin" class="control-label">
                                    {{ __('menu.dish_cost.profit_margin') }}
                                </label>
                                <div class="margin-input-group">
                                    <input type="number" id="profit-margin" name="profit_margin" 
                                           class="form-input margin-input" min="0" max="500" step="1" value="70">
                                    <span class="input-suffix">%</span>
                                </div>
                                <div class="margin-presets">
                                    <button type="button" class="preset-btn" data-margin="50">50%</button>
                                    <button type="button" class="preset-btn" data-margin="70">70%</button>
                                    <button type="button" class="preset-btn active" data-margin="100">100%</button>
                                    <button type="button" class="preset-btn" data-margin="150">150%</button>
                                </div>
                            </div>
                            
                            <div class="pricing-results">
                                <div class="price-item">
                                    <div class="price-label">{{ __('menu.dish_cost.suggested_price') }}</div>
                                    <div class="price-value suggested" id="suggested-price">£0.00</div>
                                </div>
                                <div class="price-item">
                                    <div class="price-label">{{ __('menu.dish_cost.final_price') }}</div>
                                    <div class="price-input-wrapper">
                                        <span class="price-currency">£</span>
                                        <input type="number" id="final-price" name="final_price" 
                                               class="price-input" min="0" step="0.01" value="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="pricing-analysis">
                                <div class="analysis-item">
                                    <div class="analysis-label">{{ __('menu.dish_cost.actual_margin') }}</div>
                                    <div class="analysis-value" id="actual-margin">0%</div>
                                </div>
                                <div class="analysis-item">
                                    <div class="analysis-label">{{ __('menu.dish_cost.profit_per_dish') }}</div>
                                    <div class="analysis-value" id="profit-per-dish">£0.00</div>
                                </div>
                                <div class="analysis-item">
                                    <div class="analysis-label">{{ __('menu.dish_cost.cost_percentage') }}</div>
                                    <div class="analysis-value" id="cost-percentage">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="summary-actions">
                    <button type="button" class="btn btn-secondary reset-calculation-btn">
                        {{ __('menu.dish_cost.reset_calculation') }}
                    </button>
                    <button type="button" class="btn btn-primary save-calculation-btn">
                        {{ __('menu.dish_cost.save_calculation') }}
                    </button>
                </div>

                <!-- Quick Tips -->
                <div class="quick-tips">
                    <h3>{{ __('menu.dish_cost.quick_tips') }}</h3>
                    <ul>
                        <li>{{ __('menu.dish_cost.tip_1') }}</li>
                        <li>{{ __('menu.dish_cost.tip_2') }}</li>
                        <li>{{ __('menu.dish_cost.tip_3') }}</li>
                        <li>{{ __('menu.dish_cost.tip_4') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Ingredient Modal -->
<div class="ingredient-modal" id="ingredient-modal" style="display: none;" role="dialog" aria-labelledby="ingredient-modal-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="ingredient-modal-title" class="modal-title">{{ __('menu.dish_cost.add_ingredient') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.dish_cost.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form class="ingredient-form" id="ingredient-form">
                <div class="form-grid grid grid-cols-1 md:grid-cols-2">
                    <!-- Ingredient Name -->
                    <div class="form-group md:col-span-2">
                        <label for="ingredient-name" class="form-label required">
                            {{ __('menu.dish_cost.ingredient_name') }}
                        </label>
                        <input type="text" id="ingredient-name" name="name" class="form-input" required
                               placeholder="{{ __('menu.dish_cost.ingredient_name_placeholder') }}">
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="ingredient-quantity" class="form-label required">
                            {{ __('menu.dish_cost.quantity') }}
                        </label>
                        <input type="number" id="ingredient-quantity" name="quantity" class="form-input" 
                               min="0" step="0.001" required placeholder="0">
                    </div>

                    <!-- Unit -->
                    <div class="form-group">
                        <label for="ingredient-unit" class="form-label required">
                            {{ __('menu.dish_cost.unit') }}
                        </label>
                        <select id="ingredient-unit" name="unit" class="form-select" required>
                            <option value="">{{ __('menu.dish_cost.select_unit') }}</option>
                            <option value="kg">{{ __('menu.dish_cost.kg') }}</option>
                            <option value="g">{{ __('menu.dish_cost.g') }}</option>
                            <option value="lb">{{ __('menu.dish_cost.lb') }}</option>
                            <option value="oz">{{ __('menu.dish_cost.oz') }}</option>
                            <option value="l">{{ __('menu.dish_cost.l') }}</option>
                            <option value="ml">{{ __('menu.dish_cost.ml') }}</option>
                            <option value="cup">{{ __('menu.dish_cost.cup') }}</option>
                            <option value="tbsp">{{ __('menu.dish_cost.tbsp') }}</option>
                            <option value="tsp">{{ __('menu.dish_cost.tsp') }}</option>
                            <option value="piece">{{ __('menu.dish_cost.piece') }}</option>
                        </select>
                    </div>

                    <!-- Cost per Unit -->
                    <div class="form-group md:col-span-2">
                        <label for="ingredient-cost" class="form-label required">
                            {{ __('menu.dish_cost.cost_per_unit') }}
                        </label>
                        <div class="price-input-wrapper">
                            <span class="price-currency">£</span>
                            <input type="number" id="ingredient-cost" name="cost_per_unit" class="form-input price-input" 
                                   min="0" step="0.001" required placeholder="0.00">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-group md:col-span-2">
                        <label for="ingredient-notes" class="form-label">
                            {{ __('menu.dish_cost.notes') }}
                        </label>
                        <textarea id="ingredient-notes" name="notes" class="form-textarea" rows="2"
                                  placeholder="{{ __('menu.dish_cost.notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary cancel-ingredient-btn">
                {{ __('menu.dish_cost.cancel') }}
            </button>
            <button type="submit" form="ingredient-form" class="btn btn-primary save-ingredient-btn">
                {{ __('menu.dish_cost.add_ingredient') }}
            </button>
        </div>
    </div>
</div>

<!-- Load Dish Modal -->
<div class="load-dish-modal" id="load-dish-modal" style="display: none;" role="dialog" aria-labelledby="load-dish-title" aria-hidden="true">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="load-dish-title" class="modal-title">{{ __('menu.dish_cost.load_existing_dish') }}</h2>
            <button type="button" class="modal-close" aria-label="{{ __('menu.dish_cost.close') }}">
                <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="dishes-list" id="dishes-list">
                <!-- Dishes will be populated here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-load-modal-btn">
                {{ __('menu.dish_cost.cancel') }}
            </button>
        </div>
    </div>
</div>
@endsection
