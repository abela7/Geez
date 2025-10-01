@extends('layouts.admin')

@section('title', __('inventory.settings.title') . ' - ' . config('app.name'))
@section('page_title', __('inventory.settings.title'))

@push('styles')
@vite(['resources/css/admin/inventory-settings.css'])
@endpush

@section('content')
<div class="settings-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">{{ __('inventory.settings.title') }}</h1>
            <p class="page-subtitle">{{ __('inventory.settings.subtitle') }}</p>
        </div>
    </div>

    <!-- Settings Tabs -->
    <div class="settings-tabs" x-data="{ activeTab: 'categories' }">
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <button @click="activeTab = 'categories'" 
                    :class="{ 'active': activeTab === 'categories' }"
                    class="tab-button">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ __('inventory.settings.categories_tab') }}
            </button>
            
            <button @click="activeTab = 'units'" 
                    :class="{ 'active': activeTab === 'units' }"
                    class="tab-button">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1"/>
                </svg>
                {{ __('inventory.settings.units_tab') }}
            </button>
            
            <button @click="activeTab = 'types'" 
                    :class="{ 'active': activeTab === 'types' }"
                    class="tab-button">
                <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                {{ __('inventory.settings.types_tab') }}
            </button>
        </div>

        <!-- Categories Tab -->
        <div x-show="activeTab === 'categories'" class="tab-content">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">{{ __('inventory.settings.categories.title') }}</h2>
                    <button class="btn btn-primary" onclick="openCategoryModal()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('inventory.settings.categories.add_category') }}
                    </button>
                </div>

                <!-- Categories Table -->
                <div class="table-container">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.settings.categories.name') }}</th>
                                <th>{{ __('inventory.settings.categories.description') }}</th>
                                <th>{{ __('inventory.settings.categories.color') }}</th>
                                <th>{{ __('inventory.settings.categories.active') }}</th>
                                <th>{{ __('inventory.settings.categories.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Categories for UI Demo -->
                            <tr>
                                <td>
                                    <div class="item-name">Spices & Herbs</div>
                                </td>
                                <td>
                                    <div class="item-description">Cooking spices, herbs, and seasonings</div>
                                </td>
                                <td>
                                    <div class="color-preview" style="background-color: #ef4444;"></div>
                                </td>
                                <td>
                                    <span class="status-badge active">{{ __('common.active') }}</span>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="item-name">Proteins</div>
                                </td>
                                <td>
                                    <div class="item-description">Meat, fish, poultry, and protein sources</div>
                                </td>
                                <td>
                                    <div class="color-preview" style="background-color: #10b981;"></div>
                                </td>
                                <td>
                                    <span class="status-badge active">{{ __('common.active') }}</span>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="item-name">Dairy</div>
                                </td>
                                <td>
                                    <div class="item-description">Milk, cheese, yogurt, and dairy products</div>
                                </td>
                                <td>
                                    <div class="color-preview" style="background-color: #3b82f6;"></div>
                                </td>
                                <td>
                                    <span class="status-badge active">{{ __('common.active') }}</span>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Units Tab -->
        <div x-show="activeTab === 'units'" class="tab-content">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">{{ __('inventory.settings.units.title') }}</h2>
                    <button class="btn btn-primary" onclick="openUnitModal()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('inventory.settings.units.add_unit') }}
                    </button>
                </div>

                <!-- Units Table -->
                <div class="table-container">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.settings.units.name') }}</th>
                                <th>{{ __('inventory.settings.units.symbol') }}</th>
                                <th>{{ __('inventory.settings.units.type') }}</th>
                                <th>{{ __('inventory.settings.units.description') }}</th>
                                <th>{{ __('inventory.settings.categories.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Units for UI Demo -->
                            <tr>
                                <td>
                                    <div class="item-name">Kilogram</div>
                                </td>
                                <td>
                                    <span class="unit-symbol">kg</span>
                                </td>
                                <td>
                                    <span class="type-badge weight">{{ __('inventory.settings.units.types.weight') }}</span>
                                </td>
                                <td>
                                    <div class="item-description">Standard unit of mass</div>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="item-name">Liter</div>
                                </td>
                                <td>
                                    <span class="unit-symbol">L</span>
                                </td>
                                <td>
                                    <span class="type-badge volume">{{ __('inventory.settings.units.types.volume') }}</span>
                                </td>
                                <td>
                                    <div class="item-description">Standard unit of volume</div>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="item-name">2L Jug</div>
                                </td>
                                <td>
                                    <span class="unit-symbol">2L jug</span>
                                </td>
                                <td>
                                    <span class="type-badge custom">{{ __('inventory.settings.units.types.custom') }}</span>
                                </td>
                                <td>
                                    <div class="item-description">Custom container size for liquids</div>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ingredient Types Tab -->
        <div x-show="activeTab === 'types'" class="tab-content">
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">{{ __('inventory.settings.types.title') }}</h2>
                    <button class="btn btn-primary" onclick="openTypeModal()">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('inventory.settings.types.add_type') }}
                    </button>
                </div>

                <!-- Types Table -->
                <div class="table-container">
                    <table class="settings-table">
                        <thead>
                            <tr>
                                <th>{{ __('inventory.settings.types.name') }}</th>
                                <th>{{ __('inventory.settings.types.measurement_type') }}</th>
                                <th>{{ __('inventory.settings.types.description') }}</th>
                                <th>{{ __('inventory.settings.types.color') }}</th>
                                <th>{{ __('inventory.settings.categories.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Types for UI Demo -->
                            <tr>
                                <td>
                                    <div class="item-name">Liquid</div>
                                </td>
                                <td>
                                    <span class="measurement-badge volume">{{ __('inventory.settings.units.types.volume') }}</span>
                                </td>
                                <td>
                                    <div class="item-description">Liquid ingredients like oils, milk, water</div>
                                </td>
                                <td>
                                    <div class="color-preview" style="background-color: #06b6d4;"></div>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="item-name">Solid</div>
                                </td>
                                <td>
                                    <span class="measurement-badge weight">{{ __('inventory.settings.units.types.weight') }}</span>
                                </td>
                                <td>
                                    <div class="item-description">Solid ingredients like meat, vegetables, fruits</div>
                                </td>
                                <td>
                                    <div class="color-preview" style="background-color: #84cc16;"></div>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="item-name">Powder</div>
                                </td>
                                <td>
                                    <span class="measurement-badge weight">{{ __('inventory.settings.units.types.weight') }}</span>
                                </td>
                                <td>
                                    <div class="item-description">Powdered ingredients like flour, spices, sugar</div>
                                </td>
                                <td>
                                    <div class="color-preview" style="background-color: #f59e0b;"></div>
                                </td>
                                <td class="actions-cell">
                                    <button class="action-btn edit" title="{{ __('common.edit') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" title="{{ __('common.delete') }}">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/admin/inventory-settings.js'])
@endpush
