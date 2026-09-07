<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HRM\Department;
use App\Models\HRM\Designation;
use App\Models\HRM\HrmEmployee;
use App\Models\HRM\Attendance;
use App\Models\HRM\LeaveRequest;
use App\Models\HRM\Payroll;
use Carbon\Carbon;

class HrmSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments
        $deptSoftware = Department::create([
            'name' => 'Software Engineering',
            'code' => 'DEP-SE',
            'description' => 'Software development and IT solutions department.',
            'status' => STATUS_ACTIVE,
        ]);

        $deptHr = Department::create([
            'name' => 'Human Resources',
            'code' => 'DEP-HR',
            'description' => 'Human resource management and recruitment.',
            'status' => STATUS_ACTIVE,
        ]);

        $deptFinance = Department::create([
            'name' => 'Finance & Accounts',
            'code' => 'DEP-FA',
            'description' => 'Financial operations, payroll, and auditing.',
            'status' => STATUS_ACTIVE,
        ]);

        $deptMarketing = Department::create([
            'name' => 'Marketing',
            'code' => 'DEP-MKT',
            'description' => 'Brand marketing, strategy, and public relations.',
            'status' => STATUS_ACTIVE,
        ]);

        // 2. Designations
        $desigSrDev = Designation::create([
            'department_id' => $deptSoftware->id,
            'name' => 'Senior Software Engineer',
            'code' => 'DES-SSE',
            'description' => 'Lead frontend and backend system development.',
            'status' => STATUS_ACTIVE,
        ]);

        $desigJrDev = Designation::create([
            'department_id' => $deptSoftware->id,
            'name' => 'Junior Software Engineer',
            'code' => 'DES-JSE',
            'description' => 'Assists in software development and testing.',
            'status' => STATUS_ACTIVE,
        ]);

        $desigHrMgr = Designation::create([
            'department_id' => $deptHr->id,
            'name' => 'HR Manager',
            'code' => 'DES-HRM',
            'description' => 'Manages company staff policies and relations.',
            'status' => STATUS_ACTIVE,
        ]);

        $desigAccountant = Designation::create([
            'department_id' => $deptFinance->id,
            'name' => 'Senior Accountant',
            'code' => 'DES-SA',
            'description' => 'Manages corporate financial ledgers and accounts.',
            'status' => STATUS_ACTIVE,
        ]);

        $desigMktExec = Designation::create([
            'department_id' => $deptMarketing->id,
            'name' => 'Marketing Executive',
            'code' => 'DES-ME',
            'description' => 'Executes social media campaigns and promotions.',
            'status' => STATUS_ACTIVE,
        ]);

        // 3. Employees
        $emp1 = HrmEmployee::create([
            'employee_code' => 'EMP-2026-001',
            'first_name' => 'Rahim',
            'last_name' => 'Uddin',
            'email' => 'rahim.uddin@example.com',
            'phone' => '+8801700000001',
            'gender' => GENDER_MALE,
            'date_of_birth' => '1995-05-15',
            'department_id' => $deptSoftware->id,
            'designation_id' => $desigSrDev->id,
            'joining_date' => '2023-01-10',
            'employment_type' => EMPLOYMENT_TYPE_FULL_TIME,
            'basic_salary' => 85000.00,
            'address' => 'House 12, Road 5, Dhanmondi, Dhaka',
            'status' => EMPLOYEE_STATUS_ACTIVE,
        ]);

        $emp2 = HrmEmployee::create([
            'employee_code' => 'EMP-2026-002',
            'first_name' => 'Nusrat',
            'last_name' => 'Jahan',
            'email' => 'nusrat.jahan@example.com',
            'phone' => '+8801800000002',
            'gender' => GENDER_FEMALE,
            'date_of_birth' => '1997-09-20',
            'department_id' => $deptHr->id,
            'designation_id' => $desigHrMgr->id,
            'joining_date' => '2023-06-01',
            'employment_type' => EMPLOYMENT_TYPE_FULL_TIME,
            'basic_salary' => 65000.00,
            'address' => 'Block C, Bashundhara R/A, Dhaka',
            'status' => EMPLOYEE_STATUS_ACTIVE,
        ]);

        $emp3 = HrmEmployee::create([
            'employee_code' => 'EMP-2026-003',
            'first_name' => 'Tanvir',
            'last_name' => 'Ahmed',
            'email' => 'tanvir.ahmed@example.com',
            'phone' => '+8801900000003',
            'gender' => GENDER_MALE,
            'date_of_birth' => '1998-11-12',
            'department_id' => $deptSoftware->id,
            'designation_id' => $desigJrDev->id,
            'joining_date' => '2024-02-15',
            'employment_type' => EMPLOYMENT_TYPE_FULL_TIME,
            'basic_salary' => 45000.00,
            'address' => 'Uttara Sector 4, Dhaka',
            'status' => EMPLOYEE_STATUS_ACTIVE,
        ]);

        $emp4 = HrmEmployee::create([
            'employee_code' => 'EMP-2026-004',
            'first_name' => 'Farhana',
            'last_name' => 'Akter',
            'email' => 'farhana.akter@example.com',
            'phone' => '+8801600000004',
            'gender' => GENDER_FEMALE,
            'date_of_birth' => '1996-03-25',
            'department_id' => $deptFinance->id,
            'designation_id' => $desigAccountant->id,
            'joining_date' => '2023-04-01',
            'employment_type' => EMPLOYMENT_TYPE_FULL_TIME,
            'basic_salary' => 60000.00,
            'address' => 'Mirpur 10, Dhaka',
            'status' => EMPLOYEE_STATUS_ACTIVE,
        ]);

        // 4. Attendances (for past 5 days)
        $employees = [$emp1, $emp2, $emp3, $emp4];
        for ($i = 4; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            foreach ($employees as $emp) {
                Attendance::create([
                    'employee_id' => $emp->id,
                    'date' => $date,
                    'check_in' => '09:00:00',
                    'check_out' => '17:00:00',
                    'status' => ATTENDANCE_STATUS_PRESENT,
                    'notes' => 'On time',
                ]);
            }
        }

        // 5. Leave Requests
        LeaveRequest::create([
            'employee_id' => $emp3->id,
            'leave_type' => LEAVE_TYPE_CASUAL,
            'start_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'days_count' => 2,
            'reason' => 'Family event',
            'status' => LEAVE_STATUS_PENDING,
        ]);

        LeaveRequest::create([
            'employee_id' => $emp1->id,
            'leave_type' => LEAVE_TYPE_SICK,
            'start_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'end_date' => Carbon::now()->subDays(9)->format('Y-m-d'),
            'days_count' => 2,
            'reason' => 'Fever and rest',
            'status' => LEAVE_STATUS_APPROVED,
        ]);

        // 6. Payroll
        $month = Carbon::now()->format('Y-m');
        foreach ($employees as $emp) {
            $allowances = 5000.00;
            $deductions = 2000.00;
            $netSalary = $emp->basic_salary + $allowances - $deductions;

            Payroll::create([
                'employee_id' => $emp->id,
                'payroll_month' => $month,
                'basic_salary' => $emp->basic_salary,
                'allowances' => $allowances,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
                'payment_status' => PAYMENT_STATUS_PAID,
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'payment_method' => 'Bank Transfer',
                'notes' => 'Monthly salary processed',
            ]);
        }
    }
}
