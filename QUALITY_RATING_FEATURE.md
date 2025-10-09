# Task Quality Rating Feature

## Overview
Added a comprehensive quality rating system for completed staff tasks. Admins can now rate task completion quality as **Bad**, **Good**, or **Excellent**, providing valuable data for staff performance evaluation.

## Features Added

### 1. Database Schema
- Added `quality_rating` enum field (bad, good, excellent) with default 'good'
- Added `quality_rating_by` to track who rated the task
- Added `quality_rating_at` timestamp for when rating was given
- Added `quality_rating_notes` for optional feedback

### 2. Model Enhancements
- **StaffTaskAssignment** model updated with:
  - Quality rating relationships and helper methods
  - `rateQuality()` method for easy rating updates
  - Quality rating scopes for filtering
  - Display attributes for UI (color, score, display name)

### 3. Controller Updates
- Enhanced `updateAssignmentStatus()` to handle quality ratings
- Added dedicated `updateQualityRating()` method
- Added route: `PUT /admin/staff/task-assignments/{assignment}/quality-rating`

### 4. User Interface
- **Main Tasks Table**: Added quality rating column showing:
  - Colored badges for rated tasks (Excellent=Purple, Good=Green, Bad=Red)
  - "Rate Quality" button for completed unrated tasks
  - Rating icons and notes indicators

- **Quality Rating Modal**: Beautiful modal with:
  - Visual rating options with icons and descriptions
  - Optional notes field
  - Intuitive card-based selection

- **Task Detail Modal**: Shows quality rating in assignment metadata

### 5. Language Support
- Added comprehensive translations in `resources/lang/en/staff.php`
- All quality rating text is translatable and follows i18n structure

## Usage Workflow

1. **Task Completion**: Staff completes a task (status = 'completed')
2. **Admin Rating**: Admin sees "Rate Quality" button in tasks table
3. **Rating Process**: Admin clicks button → modal opens → selects rating → adds notes → saves
4. **Display**: Quality rating appears with colored badge and optional notes icon
5. **Performance Data**: Rating data available for staff performance analysis

## Quality Rating Levels

- **Excellent** (Purple): Outstanding work quality - Score: 3
- **Good** (Green): Meets expectations - Score: 2 (Default)
- **Bad** (Red): Needs improvement - Score: 1

## Technical Implementation

### Model Methods
```php
// Rate a task assignment
$assignment->rateQuality('excellent', 'Outstanding attention to detail');

// Check if rated
$assignment->hasQualityRating();

// Get display attributes
$assignment->quality_rating_display; // "Excellent"
$assignment->quality_rating_color;   // "#8B5CF6"
$assignment->quality_rating_score;   // 3
```

### Filtering & Queries
```php
// Get assignments needing rating
StaffTaskAssignment::needingQualityRating()->get();

// Filter by rating
StaffTaskAssignment::withQualityRating('excellent')->get();

// Get rated assignments
StaffTaskAssignment::qualityRated()->get();
```

## Future Performance Analytics

The quality rating data can be used for:
- Staff performance scorecards
- Quality trends over time
- Training needs identification
- Performance-based incentives
- Team quality comparisons

## Files Modified

1. **Migration**: `2025_10_09_184610_add_quality_rating_to_staff_task_assignments_table.php`
2. **Model**: `app/Models/StaffTaskAssignment.php`
3. **Controller**: `app/Http/Controllers/Admin/StaffTasksController.php`
4. **Routes**: `routes/web.php`
5. **Views**: 
   - `resources/views/admin/staff/tasks.blade.php`
   - `resources/views/admin/staff/tasks-modal-content.blade.php`
6. **Languages**: `resources/lang/en/staff.php`

## Testing

The feature is ready for testing:
1. Create and assign a task
2. Mark it as completed
3. Visit `/admin/staff/tasks`
4. Click "Rate Quality" button on completed task
5. Select rating and add notes
6. Verify rating displays correctly

This enhancement provides a solid foundation for comprehensive staff performance evaluation based on task completion quality.
