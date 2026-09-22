<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create main student demo user: Christian Romano
        $user = User::create([
            'name'              => 'Christian Romano',
            'username'          => 'christian_romano',
            'email'             => 'christian.romano@example.com',
            'password'          => Hash::make('password123'),
            'google_id'         => 'google_demo_christian_12345',
            'email_verified_at' => now(),
        ]);

        // 2. Create sample categories
        $academic = Category::create([
            'user_id'     => $user->id,
            'name'        => 'Academics (BSIT)',
            'color'       => '#6366f1', // Indigo
            'icon'        => '🎓',
            'description' => 'College schoolwork, projects, and lab exercises',
        ]);

        $projects = Category::create([
            'user_id'     => $user->id,
            'name'        => 'Web Systems (WST21)',
            'color'       => '#0ea5e9', // Sky Blue
            'icon'        => '💻',
            'description' => 'Laravel task manager and web programming',
        ]);

        $personal = Category::create([
            'user_id'     => $user->id,
            'name'        => 'Personal & Life',
            'color'       => '#10b981', // Emerald
            'icon'        => '🌱',
            'description' => 'Personal errands, habits, and self-improvement',
        ]);

        $work = Category::create([
            'user_id'     => $user->id,
            'name'        => 'Work & Career',
            'color'       => '#f59e0b', // Amber
            'icon'        => '💼',
            'description' => 'Freelance tasks and career preparation',
        ]);

        // 3. Create diverse tasks

        // Task 1: Complete Laravel Project (Due Today, Urgent, In Progress)
        $t1 = Task::create([
            'user_id'     => $user->id,
            'category_id' => $projects->id,
            'task_name'   => 'Finalize Laravel Personal Task Manager Project',
            'description' => 'Review all requirements: Routes, Controller, Model, Database, Blade views, Auth, Subtasks, and Kanban Board.',
            'status'      => 'In Progress',
            'priority'    => 'Urgent',
            'due_date'    => Carbon::today(),
        ]);
        Subtask::create(['task_id' => $t1->id, 'title' => 'Implement PostgreSQL/MySQL database migrations', 'is_completed' => true]);
        Subtask::create(['task_id' => $t1->id, 'title' => 'Build authentication with Google OAuth integration', 'is_completed' => true]);
        Subtask::create(['task_id' => $t1->id, 'title' => 'Create Kanban Board view & Interactive Calendar', 'is_completed' => false]);
        Subtask::create(['task_id' => $t1->id, 'title' => 'Record demo walkthrough video', 'is_completed' => false]);

        // Task 2: Overdue Task (High Priority, Pending)
        $t2 = Task::create([
            'user_id'     => $user->id,
            'category_id' => $academic->id,
            'task_name'   => 'Submit Database Management Lab Report 3',
            'description' => 'Normalize tables to 3NF and generate ER diagram documentation.',
            'status'      => 'Pending',
            'priority'    => 'High',
            'due_date'    => Carbon::today()->subDays(2),
        ]);
        Subtask::create(['task_id' => $t2->id, 'title' => 'Draw ERD diagram in Draw.io', 'is_completed' => true]);
        Subtask::create(['task_id' => $t2->id, 'title' => 'Export SQL DDL schema', 'is_completed' => false]);

        // Task 3: Upcoming Task (Medium Priority, Pending)
        $t3 = Task::create([
            'user_id'     => $user->id,
            'category_id' => $academic->id,
            'task_name'   => 'Prepare for Data Structures & Algorithms Midterm Exam',
            'description' => 'Study binary search trees, graph traversals (BFS/DFS), and time complexity (Big-O).',
            'status'      => 'Pending',
            'priority'    => 'High',
            'due_date'    => Carbon::today()->addDays(5),
        ]);
        Subtask::create(['task_id' => $t3->id, 'title' => 'Review Module 4 Slides', 'is_completed' => false]);
        Subtask::create(['task_id' => $t3->id, 'title' => 'Solve 5 LeetCode Tree problems', 'is_completed' => false]);

        // Task 4: Completed Task (Low Priority)
        $t4 = Task::create([
            'user_id'      => $user->id,
            'category_id'  => $personal->id,
            'task_name'    => 'Setup XAMPP Local Development Environment',
            'description'  => 'Install PHP 8.2, Composer, and configure Apache virtual hosts.',
            'status'       => 'Completed',
            'priority'     => 'Medium',
            'due_date'     => Carbon::today()->subDays(4),
            'completed_at' => Carbon::now()->subDays(3),
        ]);
        Subtask::create(['task_id' => $t4->id, 'title' => 'Download XAMPP with PHP 8.2', 'is_completed' => true]);
        Subtask::create(['task_id' => $t4->id, 'title' => 'Verify Composer installation', 'is_completed' => true]);

        // Task 5: Upcoming Personal Task
        $t5 = Task::create([
            'user_id'     => $user->id,
            'category_id' => $personal->id,
            'task_name'   => 'Grocery Shopping & Meal Prep for the Week',
            'description' => 'Buy fresh vegetables, lean meat, and healthy snacks.',
            'status'      => 'Pending',
            'priority'    => 'Low',
            'due_date'    => Carbon::today()->addDays(2),
        ]);

        // Task 6: Career task
        $t6 = Task::create([
            'user_id'     => $user->id,
            'category_id' => $work->id,
            'task_name'   => 'Update GitHub Portfolio & LinkedIn Profile',
            'description' => 'Add recent Laravel projects with clean README badges and live demo links.',
            'status'      => 'In Progress',
            'priority'    => 'Medium',
            'due_date'    => Carbon::today()->addDays(7),
        ]);
    }
}
