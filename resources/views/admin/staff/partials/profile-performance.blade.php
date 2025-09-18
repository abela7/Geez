<div class="space-y-6">
    <!-- Performance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">
                @if ($stats['average_performance_rating'])
                    {{ number_format($stats['average_performance_rating'], 1) }}/5
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.overall_rating') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $performanceReviews->count() }}</div>
            <div class="text-sm text-secondary">{{ __('staff.total_reviews') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">
                @if ($performanceReviews->count() > 0)
                    {{ $performanceReviews->first()->review_date->format('M Y') }}
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.last_review') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">
                @if ($performanceReviews->count() >= 2)
                    @php
                        $latest = $performanceReviews->first()->overall_rating;
                        $previous = $performanceReviews->skip(1)->first()->overall_rating;
                        $trend = $latest - $previous;
                    @endphp
                    @if ($trend > 0)
                        <i class="fas fa-arrow-up text-green-600"></i> +{{ number_format($trend, 1) }}
                    @elseif ($trend < 0)
                        <i class="fas fa-arrow-down text-red-600"></i> {{ number_format($trend, 1) }}
                    @else
                        <i class="fas fa-minus text-gray-600"></i> 0
                    @endif
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.rating_trend') }}</div>
        </div>
    </div>

    <!-- Latest Performance Review -->
    @if ($performanceReviews->count() > 0)
    @php $latestReview = $performanceReviews->first(); @endphp
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.latest_performance_review') }}</h3>
        <div class="bg-background border border-main rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-medium text-primary">{{ __('staff.review_period') }}: {{ $latestReview->review_period_start->format('M d') }} - {{ $latestReview->review_period_end->format('M d, Y') }}</h4>
                    <p class="text-sm text-secondary">{{ __('staff.reviewed_by') }}: {{ $latestReview->reviewer->full_name ?? __('common.unknown') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($latestReview->overall_rating, 1) }}/5</div>
                    <div class="text-sm text-secondary">{{ __('staff.overall_rating') }}</div>
                </div>
            </div>

            <!-- Rating Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-lg font-semibold text-primary">{{ number_format($latestReview->punctuality_rating, 1) }}/5</div>
                    <div class="text-sm text-secondary">{{ __('staff.punctuality') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-primary">{{ number_format($latestReview->quality_rating, 1) }}/5</div>
                    <div class="text-sm text-secondary">{{ __('staff.quality') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-primary">{{ number_format($latestReview->teamwork_rating, 1) }}/5</div>
                    <div class="text-sm text-secondary">{{ __('staff.teamwork') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-semibold text-primary">{{ number_format($latestReview->customer_service_rating, 1) }}/5</div>
                    <div class="text-sm text-secondary">{{ __('staff.customer_service') }}</div>
                </div>
            </div>

            <!-- Review Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if ($latestReview->strengths)
                <div>
                    <h5 class="font-medium text-primary mb-2">{{ __('staff.strengths') }}</h5>
                    <p class="text-secondary text-sm">{{ $latestReview->strengths }}</p>
                </div>
                @endif

                @if ($latestReview->areas_for_improvement)
                <div>
                    <h5 class="font-medium text-primary mb-2">{{ __('staff.areas_for_improvement') }}</h5>
                    <p class="text-secondary text-sm">{{ $latestReview->areas_for_improvement }}</p>
                </div>
                @endif

                @if ($latestReview->goals)
                <div class="lg:col-span-2">
                    <h5 class="font-medium text-primary mb-2">{{ __('staff.goals') }}</h5>
                    <p class="text-secondary text-sm">{{ $latestReview->goals }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Performance History -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">{{ __('staff.performance_history') }}</h3>
            <button class="btn btn-secondary btn-sm" onclick="alert('{{ __('common.coming_soon') }}')">
                <i class="fas fa-plus mr-2"></i>{{ __('staff.new_review') }}
            </button>
        </div>

        @if ($performanceReviews->count() > 1)
        <div class="bg-background border border-main rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-main">
                    <thead class="bg-card">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.review_period') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.overall_rating') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.punctuality') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.quality') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.teamwork') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.reviewer') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-main">
                        @foreach ($performanceReviews->skip(1) as $review)
                        <tr class="hover:bg-card">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $review->review_period_start->format('M d') }} - {{ $review->review_period_end->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-medium">
                                {{ number_format($review->overall_rating, 1) }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ number_format($review->punctuality_rating, 1) }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ number_format($review->quality_rating, 1) }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ number_format($review->teamwork_rating, 1) }}/5
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $review->reviewer->full_name ?? __('common.unknown') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @elseif ($performanceReviews->count() === 0)
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-star text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('staff.no_performance_reviews') }}</h3>
            <p class="text-secondary">{{ __('staff.no_performance_description') }}</p>
        </div>
        @endif
    </div>

    <!-- Performance Trends (Placeholder) -->
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.performance_trends') }}</h3>
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-chart-line text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('common.coming_soon') }}</h3>
            <p class="text-secondary">{{ __('staff.performance_chart_description') }}</p>
        </div>
    </div>
</div>
