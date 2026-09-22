# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Primary users are college students, including Christian Romano (BSIT Section 4, 2nd Year), managing academic and personal work. They use TaskManager to keep assignments, projects, deadlines, and day-to-day to-dos organized.

## Product Purpose

TaskManager is a personal task manager built as the WST21-PM-2026-SF Web Systems and Technologies course requirement. It helps students capture, organize, track, and complete their work across several complementary views.

## Positioning

TaskManager combines passwordless, Claude.ai-style email OTP authentication with student-focused task management: searchable and sortable task lists, a Kanban workflow, a due-date calendar, category-based organization, and visible subtask progress. Google OAuth is available as an alternative sign-in path.

## Operating Context

Users manage their own academic and personal workload. They create tasks with a status, priority, optional due date, category, description, and subtasks; then review that work from the dashboard, task list, Kanban board, or monthly calendar.

## Capabilities and Constraints

- Laravel 12 application running on PHP 8.2 with a MySQL database named `mytaskmanager`.
- Passwordless authentication uses six-digit email OTPs; Google OAuth is provided through Laravel Socialite.
- The dashboard presents total, pending, completed, overdue, due-today, and upcoming task statistics, with urgent-task highlights.
- Tasks support search, filtering, sorting, priorities, due dates, statuses, categories, and subtasks with completion progress.
- Categories use customizable emoji icons and colors.
- Each user may access only their own tasks and related data.
- Email and Google OAuth credentials are configured locally in `.env`; they must not be exposed in product documentation or client code.

## Brand Commitments

- Product name: TaskManager (Personal Task Manager).
- Project code: WST21-PM-2026-SF.
- The product serves a student-oriented, personal task-management use case.

## Evidence on Hand

- The Laravel codebase implements the task, category, subtask, dashboard, authentication, Kanban, and calendar workflows.
- Email delivery and Google OAuth use real local credentials configured in `.env`.
- No public testimonials, benchmarks, pricing claims, or external marketing assets have been confirmed; future work must not fabricate them.

## Product Principles

1. Keep student work visible and actionable across time, workflow status, and priority.
2. Make account access low-friction without weakening ownership boundaries.
3. Let people organize work in the view that fits the moment: list, board, calendar, or dashboard.
4. Make progress and deadlines easy to understand at a glance.
5. Preserve the privacy of each user's personal tasks.

## Accessibility & Inclusion

The primary audience is college students. No additional product-specific accessibility standard has been confirmed yet; future interface work should retain accessible, understandable task-management workflows.
