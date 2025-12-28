# Project Resubmission Feature - Implementation Guide

## Overview
This feature allows students to resubmit their project with a new title after it has been rejected by the committee.

## Implementation Details

### 1. Database Schema
The `projects` table uses the `approved` field to track project status:
- `approved = null` → Pending approval
- `approved = 1` (true) → Approved
- `approved = 0` (false) → Rejected/Refused

### 2. Modified Files

#### a) Student FYP View (`resources/views/fyp/index.blade.php`)
- Added error message display for validation failures
- Added a resubmission form that appears only when `approved === false`
- The form includes:
  - A warning message with an icon indicating rejection
  - Input field for new project title
  - Note that supervisor assignment remains the same
  - Submit button to resubmit the project

#### b) Project Controller (`app/Http/Controllers/ProjectController.php`)
- Added new `resubmit()` method that:
  - Validates the student owns the project
  - Checks if project was actually rejected
  - Validates the new title
  - Updates the project with new title
  - Resets approval status to `null` (Pending)
  - Resets `approved_at` and `approved_by` to null
  - Sends notifications to all committee members

#### c) Routes (`routes/web.php`)
- Added new route: `POST /projects/{project}/resubmit`
- Route name: `projects.resubmit`
- Maps to: `ProjectController@resubmit`

### 3. Workflow

#### Student Side:
1. Student logs in and navigates to FYP page
2. If project status is "Refused", a red-highlighted resubmission form appears
3. Student enters a new project title
4. Student clicks "Resubmit Project"
5. Backend validates and updates the project
6. Status changes from "Refused" to "Pending"
7. Student sees success message: "Your project has been resubmitted successfully and is now pending approval."

#### Committee Side:
1. Committee members receive notification about the resubmission
2. Resubmitted project appears in the "Project Approval" page with "Pending" status
3. Committee can approve or reject the project again
4. The project maintains the same ID (it's an update, not a new project)

### 4. Security Features
- Only the project owner (student) can resubmit
- Only projects with `approved = false` can be resubmitted
- Route is protected by authentication middleware
- Form validation prevents empty or invalid titles

### 5. Notifications
When a project is resubmitted:
- All committee members receive a notification
- Notification message: "Student [Name] has resubmitted their project with a new title: [Title]. Please review for approval."

## Testing Steps

### Test 1: Normal Resubmission Flow
1. Create a student account and register a project
2. Log in as committee and reject the project
3. Log back in as student
4. Verify the resubmission form appears with red warning
5. Enter a new project title
6. Submit the form
7. Verify success message appears
8. Check that status changed to "Pending"
9. Log in as committee
10. Verify project appears in pending list with new title

### Test 2: Authorization Check
1. Try to access resubmission URL directly for another student's project
2. Should redirect with "Unauthorized access" error

### Test 3: Status Validation
1. Try to resubmit a project that is "Pending" or "Approved"
2. Should redirect with error: "This project has not been rejected and cannot be resubmitted."

### Test 4: Validation Check
1. Try to submit empty title
2. Should show validation error: "The title field is required."
3. Try to submit very long title (over 255 characters)
4. Should show validation error

### Test 5: Notification Check
1. Resubmit a project as student
2. Log in as committee member
3. Check notifications - should see resubmission notification

## Database Queries for Testing

```sql
-- Check project approval status
SELECT id, title, approved, approved_at, approved_by, created_at, updated_at 
FROM projects 
WHERE user_id = [STUDENT_ID];

-- Check notifications sent to committee
SELECT * FROM student_notifications 
WHERE user_id IN (SELECT id FROM users WHERE role = 'committee') 
ORDER BY created_at DESC;

-- Reset a project to rejected status for testing
UPDATE projects 
SET approved = 0, approved_at = NOW(), approved_by = [COMMITTEE_ID] 
WHERE id = [PROJECT_ID];
```

## Notes
- The supervisor assignment remains unchanged during resubmission
- The project ID remains the same (update operation, not insert)
- Committee can approve/reject resubmitted projects multiple times
- Each resubmission sends new notifications to committee members
- Old title is replaced with new title in the database
