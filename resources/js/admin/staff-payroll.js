/**
 * Staff Payroll Management JavaScript
 * Handles payroll processing, calculations, employee management, and reporting
 */

// Payroll Manager Alpine.js Component
function payrollManager() {
    return {
        // State Management
        currentView: 'dashboard',
        showPayrollModal: false,
        showEmployeeModal: false,
        showBonusModal: false,
        showDeductionsModal: false,
        editingEmployee: null,
        
        // Data
        employees: [],
        payrollHistory: [],
        recentActivity: [],
        
        // Calendar
        currentDate: new Date(),
        calendarDays: [],
        
        // Filters & Search
        filters: {
            department: '',
            payrollStatus: ''
        },
        searchQuery: '',
        sortBy: 'name',
        sortOrder: 'asc',
        
        // History Filters
        historyFilters: {
            period: '',
            type: '',
            status: '',
            startDate: '',
            endDate: ''
        },
        historySearchQuery: '',
        
        // Selection
        selectedEmployees: [],
        selectedHistory: [],
        
        // Pagination
        currentPage: 1,
        itemsPerPage: 10,
        
        // Form Data
        payrollForm: {
            period: '',
            type: 'regular',
            periodStart: '',
            periodEnd: '',
            selectedEmployees: [],
            notes: ''
        },
        
        employeeForm: {
            baseSalary: '',
            hourlyRate: '',
            overtimeRate: '',
            paymentFrequency: 'monthly',
            bonuses: [],
            deductions: [],
            bankName: '',
            accountNumber: ''
        },
        
        bonusForm: {
            type: 'performance',
            amount: '',
            description: '',
            selectedEmployees: []
        },
        
        deductionForm: {
            type: 'tax',
            amount: '',
            description: '',
            selectedEmployees: []
        },
        
        // Computed Properties
        get payrollStats() {
            const stats = {
                totalPayroll: 0,
                employeesPaid: 0,
                pendingApprovals: 0,
                avgSalary: 0
            };
            
            this.employees.forEach(employee => {
                stats.totalPayroll += employee.net_salary || 0;
                if (employee.payroll_status === 'processed') stats.employeesPaid++;
                if (employee.payroll_status === 'pending') stats.pendingApprovals++;
            });
            
            stats.avgSalary = this.employees.length > 0 ? stats.totalPayroll / this.employees.length : 0;
            
            return stats;
        },
        
        get filteredEmployees() {
            let filtered = [...this.employees];
            
            // Apply search filter
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(employee => 
                    employee.name.toLowerCase().includes(query) ||
                    employee.position.toLowerCase().includes(query) ||
                    employee.department.toLowerCase().includes(query)
                );
            }
            
            // Apply filters
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    filtered = filtered.filter(employee => employee[key] === this.filters[key]);
                }
            });
            
            // Apply sorting
            filtered.sort((a, b) => {
                let aValue = a[this.sortBy];
                let bValue = b[this.sortBy];
                
                // Handle salary sorting
                if (this.sortBy === 'salary') {
                    aValue = a.base_salary;
                    bValue = b.base_salary;
                }
                
                if (typeof aValue === 'string') {
                    aValue = aValue.toLowerCase();
                    bValue = bValue.toLowerCase();
                }
                
                if (this.sortOrder === 'desc') {
                    return bValue > aValue ? 1 : -1;
                } else {
                    return aValue > bValue ? 1 : -1;
                }
            });
            
            return filtered;
        },
        
        get filteredHistory() {
            let filtered = [...this.payrollHistory];
            
            // Apply search filter
            if (this.historySearchQuery) {
                const query = this.historySearchQuery.toLowerCase();
                filtered = filtered.filter(record => 
                    record.employee_name.toLowerCase().includes(query) ||
                    record.employee_id.toLowerCase().includes(query) ||
                    record.type.toLowerCase().includes(query)
                );
            }
            
            // Apply filters
            Object.keys(this.historyFilters).forEach(key => {
                if (this.historyFilters[key] && key !== 'startDate' && key !== 'endDate') {
                    if (key === 'period') {
                        // Handle period filtering
                        const now = new Date();
                        const recordDate = new Date(record.processed_date);
                        
                        switch (this.historyFilters[key]) {
                            case 'this_month':
                                filtered = filtered.filter(record => {
                                    const date = new Date(record.processed_date);
                                    return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
                                });
                                break;
                            case 'last_month':
                                filtered = filtered.filter(record => {
                                    const date = new Date(record.processed_date);
                                    const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1);
                                    return date.getMonth() === lastMonth.getMonth() && date.getFullYear() === lastMonth.getFullYear();
                                });
                                break;
                            // Add more period filters as needed
                        }
                    } else {
                        filtered = filtered.filter(record => record[key] === this.historyFilters[key]);
                    }
                }
            });
            
            // Apply date range filter
            if (this.historyFilters.startDate && this.historyFilters.endDate) {
                const startDate = new Date(this.historyFilters.startDate);
                const endDate = new Date(this.historyFilters.endDate);
                filtered = filtered.filter(record => {
                    const recordDate = new Date(record.processed_date);
                    return recordDate >= startDate && recordDate <= endDate;
                });
            }
            
            return filtered;
        },
        
        get currentMonthYear() {
            return this.currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },
        
        get totalPages() {
            return Math.ceil(this.filteredHistory.length / this.itemsPerPage);
        },
        
        get paginationPages() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        },
        
        // Initialization
        async init() {
            console.log('Initializing Payroll Manager...');
            
            // Load initial data
            await this.loadEmployees();
            await this.loadPayrollHistory();
            await this.loadRecentActivity();
            
            // Generate calendar
            this.generateCalendar();
            
            console.log('Payroll Manager initialized successfully');
        },
        
        // Data Loading
        async loadEmployees() {
            try {
                // Simulate API call - replace with actual endpoint
                this.employees = await this.mockApiCall('/api/admin/staff/payroll/employees', [
                    {
                        id: 1,
                        name: 'John Smith',
                        position: 'Head Chef',
                        department: 'kitchen',
                        avatar: '/images/avatars/john.jpg',
                        base_salary: 4500,
                        hourly_rate: 25,
                        overtime_rate: 37.5,
                        bonuses: 200,
                        deductions: 450,
                        net_salary: 4250,
                        payroll_status: 'processed',
                        last_paid: '2024-01-01',
                        next_payday: '2024-02-01',
                        payment_frequency: 'monthly',
                        bank_name: 'First National Bank',
                        account_number: '****1234'
                    },
                    {
                        id: 2,
                        name: 'Sarah Johnson',
                        position: 'Server Manager',
                        department: 'service',
                        avatar: '/images/avatars/sarah.jpg',
                        base_salary: 3200,
                        hourly_rate: 18,
                        overtime_rate: 27,
                        bonuses: 150,
                        deductions: 320,
                        net_salary: 3030,
                        payroll_status: 'pending',
                        last_paid: '2024-01-01',
                        next_payday: '2024-02-01',
                        payment_frequency: 'monthly',
                        bank_name: 'Community Bank',
                        account_number: '****5678'
                    },
                    {
                        id: 3,
                        name: 'Mike Wilson',
                        position: 'Kitchen Assistant',
                        department: 'kitchen',
                        avatar: '/images/avatars/mike.jpg',
                        base_salary: 2800,
                        hourly_rate: 15,
                        overtime_rate: 22.5,
                        bonuses: 100,
                        deductions: 280,
                        net_salary: 2620,
                        payroll_status: 'active',
                        last_paid: '2024-01-01',
                        next_payday: '2024-02-01',
                        payment_frequency: 'monthly',
                        bank_name: 'City Bank',
                        account_number: '****9012'
                    },
                    {
                        id: 4,
                        name: 'Lisa Brown',
                        position: 'Waitress',
                        department: 'service',
                        avatar: '/images/avatars/lisa.jpg',
                        base_salary: 2400,
                        hourly_rate: 12,
                        overtime_rate: 18,
                        bonuses: 80,
                        deductions: 240,
                        net_salary: 2240,
                        payroll_status: 'processed',
                        last_paid: '2024-01-01',
                        next_payday: '2024-02-01',
                        payment_frequency: 'monthly',
                        bank_name: 'Regional Bank',
                        account_number: '****3456'
                    }
                ]);
                
            } catch (error) {
                console.error('Error loading employees:', error);
                this.showNotification('Error loading employees', 'error');
            }
        },
        
        async loadPayrollHistory() {
            try {
                // Simulate API call - replace with actual endpoint
                this.payrollHistory = await this.mockApiCall('/api/admin/staff/payroll/history', [
                    {
                        id: 1,
                        employee_id: 1,
                        employee_name: 'John Smith',
                        employee_avatar: '/images/avatars/john.jpg',
                        period: 'January 2024',
                        type: 'salary',
                        gross_amount: 4500,
                        deductions: 450,
                        net_amount: 4050,
                        status: 'processed',
                        processed_date: '2024-01-31'
                    },
                    {
                        id: 2,
                        employee_id: 2,
                        employee_name: 'Sarah Johnson',
                        employee_avatar: '/images/avatars/sarah.jpg',
                        period: 'January 2024',
                        type: 'salary',
                        gross_amount: 3200,
                        deductions: 320,
                        net_amount: 2880,
                        status: 'processed',
                        processed_date: '2024-01-31'
                    },
                    {
                        id: 3,
                        employee_id: 1,
                        employee_name: 'John Smith',
                        employee_avatar: '/images/avatars/john.jpg',
                        period: 'January 2024',
                        type: 'bonus',
                        gross_amount: 500,
                        deductions: 50,
                        net_amount: 450,
                        status: 'processed',
                        processed_date: '2024-01-15'
                    }
                ]);
                
            } catch (error) {
                console.error('Error loading payroll history:', error);
                this.showNotification('Error loading payroll history', 'error');
            }
        },
        
        async loadRecentActivity() {
            try {
                // Simulate API call - replace with actual endpoint
                this.recentActivity = await this.mockApiCall('/api/admin/staff/payroll/activity', [
                    {
                        id: 1,
                        type: 'processed',
                        title: 'Monthly Payroll Processed',
                        description: 'January 2024 payroll for 4 employees',
                        amount: 12650,
                        time: '2 hours ago'
                    },
                    {
                        id: 2,
                        type: 'bonus',
                        title: 'Performance Bonus Added',
                        description: 'Bonus for John Smith - Excellent performance',
                        amount: 500,
                        time: '1 day ago'
                    },
                    {
                        id: 3,
                        type: 'pending',
                        title: 'Payroll Pending Approval',
                        description: 'February 2024 payroll awaiting approval',
                        amount: 13200,
                        time: '3 days ago'
                    }
                ]);
                
            } catch (error) {
                console.error('Error loading recent activity:', error);
            }
        },
        
        // Mock API call for development
        async mockApiCall(endpoint, mockData) {
            return new Promise(resolve => {
                setTimeout(() => resolve(mockData), 300);
            });
        },
        
        // View Management
        setView(view) {
            this.currentView = view;
            console.log(`Switched to ${view} view`);
        },
        
        // Calendar Management
        generateCalendar() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
            
            const days = [];
            const today = new Date();
            
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                const isToday = date.toDateString() === today.toDateString();
                const isCurrentMonth = date.getMonth() === month;
                const isPayday = date.getDate() === 1 || date.getDate() === 15; // Example payday logic
                
                days.push({
                    date: date.toISOString().split('T')[0],
                    day: date.getDate(),
                    isToday,
                    otherMonth: !isCurrentMonth,
                    isPayday,
                    hasPayroll: Math.random() > 0.8, // Mock payroll data
                    amount: Math.random() > 0.8 ? Math.floor(Math.random() * 5000) + 1000 : 0
                });
            }
            
            this.calendarDays = days;
        },
        
        previousMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.generateCalendar();
        },
        
        nextMonth() {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.generateCalendar();
        },
        
        viewDayPayroll(day) {
            if (day.hasPayroll) {
                console.log('Viewing payroll for', day.date);
                // Implement day payroll view
            }
        },
        
        // Modal Management
        openPayrollModal() {
            this.resetPayrollForm();
            this.showPayrollModal = true;
        },
        
        closePayrollModal() {
            this.showPayrollModal = false;
            this.resetPayrollForm();
        },
        
        openEmployeeModal(employee = null) {
            this.editingEmployee = employee;
            if (employee) {
                this.employeeForm = {
                    baseSalary: employee.base_salary,
                    hourlyRate: employee.hourly_rate,
                    overtimeRate: employee.overtime_rate,
                    paymentFrequency: employee.payment_frequency,
                    bonuses: [],
                    deductions: [],
                    bankName: employee.bank_name,
                    accountNumber: employee.account_number
                };
            } else {
                this.resetEmployeeForm();
            }
            this.showEmployeeModal = true;
        },
        
        closeEmployeeModal() {
            this.showEmployeeModal = false;
            this.editingEmployee = null;
            this.resetEmployeeForm();
        },
        
        openBonusModal() {
            this.resetBonusForm();
            this.showBonusModal = true;
        },
        
        closeBonusModal() {
            this.showBonusModal = false;
            this.resetBonusForm();
        },
        
        openDeductionsModal() {
            this.resetDeductionForm();
            this.showDeductionsModal = true;
        },
        
        closeDeductionsModal() {
            this.showDeductionsModal = false;
            this.resetDeductionForm();
        },
        
        // Form Reset Functions
        resetPayrollForm() {
            this.payrollForm = {
                period: '',
                type: 'regular',
                periodStart: '',
                periodEnd: '',
                selectedEmployees: [],
                notes: ''
            };
        },
        
        resetEmployeeForm() {
            this.employeeForm = {
                baseSalary: '',
                hourlyRate: '',
                overtimeRate: '',
                paymentFrequency: 'monthly',
                bonuses: [],
                deductions: [],
                bankName: '',
                accountNumber: ''
            };
        },
        
        resetBonusForm() {
            this.bonusForm = {
                type: 'performance',
                amount: '',
                description: '',
                selectedEmployees: []
            };
        },
        
        resetDeductionForm() {
            this.deductionForm = {
                type: 'tax',
                amount: '',
                description: '',
                selectedEmployees: []
            };
        },
        
        // Payroll Processing
        async processPayroll() {
            try {
                if (this.payrollForm.selectedEmployees.length === 0) {
                    this.showNotification('Please select employees to process payroll', 'error');
                    return;
                }
                
                // Simulate API call
                console.log('Processing payroll:', this.payrollForm);
                
                // Update employee statuses
                this.employees.forEach(employee => {
                    if (this.payrollForm.selectedEmployees.includes(employee.id)) {
                        employee.payroll_status = 'processed';
                        employee.last_paid = new Date().toISOString().split('T')[0];
                    }
                });
                
                this.showNotification('Payroll processed successfully', 'success');
                this.closePayrollModal();
                await this.loadRecentActivity(); // Refresh activity
                
            } catch (error) {
                console.error('Error processing payroll:', error);
                this.showNotification('Error processing payroll', 'error');
            }
        },
        
        async processMonthlyPayroll() {
            // Set up monthly payroll with all active employees
            this.payrollForm.period = 'current_month';
            this.payrollForm.type = 'regular';
            this.payrollForm.selectedEmployees = this.employees
                .filter(emp => emp.payroll_status === 'active')
                .map(emp => emp.id);
            this.showPayrollModal = true;
        },
        
        // Employee Management
        async saveEmployeePayroll() {
            try {
                if (this.editingEmployee) {
                    // Update existing employee
                    const employee = this.employees.find(emp => emp.id === this.editingEmployee.id);
                    if (employee) {
                        employee.base_salary = parseFloat(this.employeeForm.baseSalary);
                        employee.hourly_rate = parseFloat(this.employeeForm.hourlyRate);
                        employee.overtime_rate = parseFloat(this.employeeForm.overtimeRate);
                        employee.payment_frequency = this.employeeForm.paymentFrequency;
                        employee.bank_name = this.employeeForm.bankName;
                        employee.account_number = this.employeeForm.accountNumber;
                        
                        // Recalculate net salary
                        employee.net_salary = employee.base_salary + employee.bonuses - employee.deductions;
                    }
                    
                    this.showNotification('Employee payroll updated successfully', 'success');
                }
                
                this.closeEmployeeModal();
                
            } catch (error) {
                console.error('Error saving employee payroll:', error);
                this.showNotification('Error saving employee payroll', 'error');
            }
        },
        
        viewEmployeePayroll(employee) {
            console.log('Viewing payroll for:', employee.name);
            // Implement detailed employee payroll view
        },
        
        editEmployeePayroll(employee) {
            this.openEmployeeModal(employee);
        },
        
        async processEmployeePayroll(employee) {
            try {
                employee.payroll_status = 'processed';
                employee.last_paid = new Date().toISOString().split('T')[0];
                
                this.showNotification(`Payroll processed for ${employee.name}`, 'success');
                
            } catch (error) {
                console.error('Error processing employee payroll:', error);
                this.showNotification('Error processing payroll', 'error');
            }
        },
        
        async generatePayslip(employee) {
            try {
                console.log('Generating payslip for:', employee.name);
                // Simulate payslip generation
                this.showNotification(`Payslip generated for ${employee.name}`, 'success');
                
            } catch (error) {
                console.error('Error generating payslip:', error);
                this.showNotification('Error generating payslip', 'error');
            }
        },
        
        async generatePayslips() {
            try {
                console.log('Generating payslips for all employees');
                this.showNotification('Payslips generated successfully', 'success');
                
            } catch (error) {
                console.error('Error generating payslips:', error);
                this.showNotification('Error generating payslips', 'error');
            }
        },
        
        // Bonus and Deduction Management
        async processBonuses() {
            try {
                if (this.bonusForm.selectedEmployees.length === 0) {
                    this.showNotification('Please select employees for bonus', 'error');
                    return;
                }
                
                // Apply bonuses to selected employees
                this.employees.forEach(employee => {
                    if (this.bonusForm.selectedEmployees.includes(employee.id)) {
                        employee.bonuses += parseFloat(this.bonusForm.amount);
                        employee.net_salary = employee.base_salary + employee.bonuses - employee.deductions;
                    }
                });
                
                this.showNotification('Bonuses applied successfully', 'success');
                this.closeBonusModal();
                
            } catch (error) {
                console.error('Error processing bonuses:', error);
                this.showNotification('Error processing bonuses', 'error');
            }
        },
        
        async processDeductions() {
            try {
                if (this.deductionForm.selectedEmployees.length === 0) {
                    this.showNotification('Please select employees for deduction', 'error');
                    return;
                }
                
                // Apply deductions to selected employees
                this.employees.forEach(employee => {
                    if (this.deductionForm.selectedEmployees.includes(employee.id)) {
                        employee.deductions += parseFloat(this.deductionForm.amount);
                        employee.net_salary = employee.base_salary + employee.bonuses - employee.deductions;
                    }
                });
                
                this.showNotification('Deductions applied successfully', 'success');
                this.closeDeductionsModal();
                
            } catch (error) {
                console.error('Error processing deductions:', error);
                this.showNotification('Error processing deductions', 'error');
            }
        },
        
        // Bonus/Deduction Form Management
        addBonus() {
            this.employeeForm.bonuses.push({ description: '', amount: 0 });
        },
        
        removeBonus(index) {
            this.employeeForm.bonuses.splice(index, 1);
        },
        
        addDeduction() {
            this.employeeForm.deductions.push({ description: '', amount: 0 });
        },
        
        removeDeduction(index) {
            this.employeeForm.deductions.splice(index, 1);
        },
        
        // Employee Selection
        selectAllEmployees() {
            this.payrollForm.selectedEmployees = this.employees.map(emp => emp.id);
        },
        
        selectNoneEmployees() {
            this.payrollForm.selectedEmployees = [];
        },
        
        selectByDepartment() {
            // Implement department-based selection
            console.log('Select by department');
        },
        
        // Filtering and Search
        applyFilters() {
            console.log('Applying filters:', this.filters);
        },
        
        searchEmployees() {
            console.log('Searching employees:', this.searchQuery);
        },
        
        sortEmployees() {
            console.log('Sorting employees by:', this.sortBy, this.sortOrder);
        },
        
        toggleSortOrder() {
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
        },
        
        // History Management
        applyHistoryFilters() {
            console.log('Applying history filters:', this.historyFilters);
        },
        
        searchHistory() {
            console.log('Searching history:', this.historySearchQuery);
        },
        
        viewPayrollRecord(record) {
            console.log('Viewing payroll record:', record);
            // Implement detailed record view
        },
        
        async downloadPayslip(record) {
            try {
                console.log('Downloading payslip for record:', record.id);
                this.showNotification('Payslip downloaded successfully', 'success');
                
            } catch (error) {
                console.error('Error downloading payslip:', error);
                this.showNotification('Error downloading payslip', 'error');
            }
        },
        
        async cancelPayroll(record) {
            if (confirm('Are you sure you want to cancel this payroll?')) {
                try {
                    record.status = 'cancelled';
                    this.showNotification('Payroll cancelled successfully', 'success');
                    
                } catch (error) {
                    console.error('Error cancelling payroll:', error);
                    this.showNotification('Error cancelling payroll', 'error');
                }
            }
        },
        
        async exportHistory() {
            try {
                console.log('Exporting payroll history');
                this.showNotification('History exported successfully', 'success');
                
            } catch (error) {
                console.error('Error exporting history:', error);
                this.showNotification('Error exporting history', 'error');
            }
        },
        
        // Selection Management
        toggleEmployeeSelection(employeeId) {
            const index = this.selectedEmployees.indexOf(employeeId);
            if (index > -1) {
                this.selectedEmployees.splice(index, 1);
            } else {
                this.selectedEmployees.push(employeeId);
            }
        },
        
        toggleHistorySelection(recordId) {
            const index = this.selectedHistory.indexOf(recordId);
            if (index > -1) {
                this.selectedHistory.splice(index, 1);
            } else {
                this.selectedHistory.push(recordId);
            }
        },
        
        toggleSelectAllHistory() {
            if (this.selectedHistory.length === this.filteredHistory.length) {
                this.selectedHistory = [];
            } else {
                this.selectedHistory = this.filteredHistory.map(record => record.id);
            }
        },
        
        // Bulk Operations
        async bulkProcessPayroll() {
            if (this.selectedEmployees.length === 0) return;
            
            try {
                this.employees.forEach(employee => {
                    if (this.selectedEmployees.includes(employee.id)) {
                        employee.payroll_status = 'processed';
                        employee.last_paid = new Date().toISOString().split('T')[0];
                    }
                });
                
                this.showNotification(`${this.selectedEmployees.length} employees processed`, 'success');
                this.selectedEmployees = [];
                
            } catch (error) {
                console.error('Error in bulk payroll processing:', error);
                this.showNotification('Error processing payroll', 'error');
            }
        },
        
        async bulkGeneratePayslips() {
            if (this.selectedEmployees.length === 0) return;
            
            try {
                console.log('Generating payslips for selected employees');
                this.showNotification(`${this.selectedEmployees.length} payslips generated`, 'success');
                this.selectedEmployees = [];
                
            } catch (error) {
                console.error('Error generating payslips:', error);
                this.showNotification('Error generating payslips', 'error');
            }
        },
        
        async bulkAddBonus() {
            if (this.selectedEmployees.length === 0) return;
            
            this.bonusForm.selectedEmployees = [...this.selectedEmployees];
            this.openBonusModal();
        },
        
        async bulkAddDeduction() {
            if (this.selectedEmployees.length === 0) return;
            
            this.deductionForm.selectedEmployees = [...this.selectedEmployees];
            this.openDeductionsModal();
        },
        
        async bulkDownloadPayslips() {
            if (this.selectedHistory.length === 0) return;
            
            try {
                console.log('Downloading payslips for selected records');
                this.showNotification(`${this.selectedHistory.length} payslips downloaded`, 'success');
                this.selectedHistory = [];
                
            } catch (error) {
                console.error('Error downloading payslips:', error);
                this.showNotification('Error downloading payslips', 'error');
            }
        },
        
        async bulkExportRecords() {
            if (this.selectedHistory.length === 0) return;
            
            try {
                console.log('Exporting selected records');
                this.showNotification(`${this.selectedHistory.length} records exported`, 'success');
                this.selectedHistory = [];
                
            } catch (error) {
                console.error('Error exporting records:', error);
                this.showNotification('Error exporting records', 'error');
            }
        },
        
        async bulkCancelPayroll() {
            if (this.selectedHistory.length === 0) return;
            
            if (confirm(`Are you sure you want to cancel ${this.selectedHistory.length} payroll records?`)) {
                try {
                    this.payrollHistory.forEach(record => {
                        if (this.selectedHistory.includes(record.id)) {
                            record.status = 'cancelled';
                        }
                    });
                    
                    this.showNotification(`${this.selectedHistory.length} payroll records cancelled`, 'success');
                    this.selectedHistory = [];
                    
                } catch (error) {
                    console.error('Error cancelling payroll records:', error);
                    this.showNotification('Error cancelling payroll records', 'error');
                }
            }
        },
        
        // Pagination
        goToPage(page) {
            this.currentPage = page;
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        
        // Calculations
        calculateTotalGross() {
            return this.payrollForm.selectedEmployees.reduce((total, employeeId) => {
                const employee = this.employees.find(emp => emp.id === employeeId);
                return total + (employee ? employee.base_salary : 0);
            }, 0);
        },
        
        calculateTotalDeductions() {
            return this.payrollForm.selectedEmployees.reduce((total, employeeId) => {
                const employee = this.employees.find(emp => emp.id === employeeId);
                return total + (employee ? employee.deductions : 0);
            }, 0);
        },
        
        calculateTotalNet() {
            return this.calculateTotalGross() - this.calculateTotalDeductions();
        },
        
        // Utility Functions
        formatCurrency(amount) {
            return new Intl.NumberFormat('en-GB', {
                style: 'currency',
                currency: 'GBP'
            }).format(amount || 0);
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
        
        formatDateTime(dateString) {
            return new Date(dateString).toLocaleString();
        },
        
        // Notifications
        showNotification(message, type = 'info') {
            // Create and show notification
            const notification = document.createElement('div');
            notification.className = `notification notification--${type}`;
            notification.textContent = message;
            
            // Style the notification
            Object.assign(notification.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                padding: '12px 24px',
                borderRadius: '8px',
                color: 'white',
                fontWeight: '500',
                zIndex: '1000',
                transform: 'translateX(100%)',
                transition: 'transform 0.3s ease'
            });
            
            // Set background color based on type
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };
            notification.style.backgroundColor = colors[type] || colors.info;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    };
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Staff Payroll JavaScript loaded');
    
    // Handle keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + P: Process payroll
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            event.preventDefault();
            const payrollContainer = document.querySelector('.payroll-container');
            if (payrollContainer) {
                payrollContainer.dispatchEvent(new CustomEvent('open-payroll-modal'));
            }
        }
        
        // Escape: Close modals
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal-overlay');
            modals.forEach(modal => {
                if (modal.style.display !== 'none') {
                    modal.dispatchEvent(new CustomEvent('close-modal'));
                }
            });
        }
    });
});

// Export for use in other modules if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { payrollManager };
}
