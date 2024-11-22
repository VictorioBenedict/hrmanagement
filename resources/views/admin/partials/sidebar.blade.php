<style>
/* Sidebar Styles */
.sidebar {
    background-color: #Q3122F; /* Sidebar background color */
    color: white; /* Default text color for the sidebar */
    height: 100vh; /* Full screen height */
    width: 250px; /* Sidebar width */
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto; /* Add scroll when content overflows */
    max-height: 100vh;
    padding-top: 20px;
}

/* Sidebar Link Styles */
.sidebar-link {
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 1em; /* Updated font size to 1.3em */
    color: white; /* Default link text color */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover effects */
}

/* Active Link Style */
.sidebar-link.active,
.sidebar-link.bg-primary {
    color: white; /* Active link text color */
    background-color: #19305C; /* Remove the background color when active */
}

/* Sidebar List Item */
.sidebar-list-item {
    list-style: none;
}

.sidebar-list-item.py-2 {
    padding-top: 10px;
    padding-bottom: 10px;
}

/* Dropdown Styles */
.sidebar-menu {
    padding-left: 20px;
}

/* Icon within sidebar link */
.sidebar-link i {
    margin-right: 0px;
}

/* Collapse Effect */
.collapse {
    display: none;
}

.collapse.show {
    display: block;
}

/* Hover Effect */
.sidebar-link:hover {
    background-color: white; /* Hover background color */
    color: #F0F1F7; /* Ensure hover text color is #F0F1F7 */
}

/* Modify font color for text in collapsed items */
.sidebar-list-item .sidebar-link {
    color: F3DADF;
}

/* Change color for active links in dropdown */
.sidebar-list-item .sidebar-link.active,
.sidebar-list-item .sidebar-link.bg-primary {
    background-color: transparent; /* Remove the background color */
    color: F3DADF; /* Text color */
}
</style>

<div class="sidebar" id="sidebar">
    <ul class="list-unstyled mb-5">
        <li style="list-style: none; padding: 0;">
            <a href="{{ route('dashboard') }}" style="text-decoration: none; color: white;">
                <h3 style="margin-left: 20px; margin-top: 20px;">Dashboard</h3>
            </a>
        </li>

        @if(Auth::user()->role == 'Admin')
        <li class="sidebar-list-item py-2">
            <a class="sidebar-link {{ request()->is('Organization/department', 'Organization/designationList') ? 'bg-primary text-F0F1F7' : '' }}" 
               href="#" data-bs-toggle="collapse" data-bs-target="#organizationDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Organization</span>
            </a>
            <ul class="collapse" id="organizationDropdown">
            
        <li class="sidebar-list-item fs-6">
            <a class="sidebar-link ms-3 {{ request()->is('Organization/department') ? 'bg-primary text-F0F1F7' : '' }}" 
            href="{{ route('organization.department') }}">
                <span class="sidebar-link-title">Department</span>
            </a>
        </li>
        <li class="sidebar-list-item fs-6">
            <a class="sidebar-link ms-3 {{ request()->is('Organization/designationList') ? 'bg-primary text-F0F1F7' : '' }}" 
            href="{{ route('organization.designationList') }}">
                <span class="sidebar-link-title">Position</span>
            </a>
        </li>

            </ul>
        </li>
        @endif

        @if(Auth::user()->role == 'Admin' || Auth::user()->role == 'System Admin')
        <li class="sidebar-list-item py-2">
            <a class="sidebar-link {{ request()->is('Employee/addEmployee', 'Employee/viewEmployee') ? 'bg-primary text-F0F1F7' : '' }}" 
               href="#" data-bs-toggle="collapse" data-bs-target="#employeeDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Employees</span>
            </a>
            <ul class="collapse" id="employeeDropdown">
                <li class="sidebar-list-item py-2 fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Employee/addEmployee') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('manageEmployee.addEmployee') }}">
                        Add Employee
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Employee/viewEmployee') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('manageEmployee.ViewEmployee') }}">
                        View Employee
                    </a>
                </li>
            </ul>
        </li>

        <li class="sidebar-list-item py-2">
            <a class="sidebar-link {{ request()->is('Leave/LeaveStatus', 'Leave/LeaveType') ? 'bg-primary text-F0F1F7' : '' }}" 
               href="#" data-bs-toggle="collapse" data-bs-target="#leaveDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Leave</span>
            </a>
            <ul class="collapse" id="leaveDropdown">
                <li class="sidebar-list-item py-2 fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Leave/LeaveStatus') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('leave.leaveStatus') }}">
                        Leave Request
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Leave/LeaveType') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('leave.leaveType') }}">
                        Leave Type
                    </a>
                </li>

        @if(Auth::user()->role == 'Admin')
        <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Status/StatusType') ? 'bg-primary text-F0F1F7' : ''}}" href="{{ route('status.list') }}">
               Status Type
            </a>
        </li>
        @endif
            </ul>
        </li>

        {{-- Admin: Document --}}
        <li class="sidebar-list-item py-2">
            <a class="sidebar-link {{ request()->is('Document/DocumentRequest', 'Document/DocumentType') ? 'bg-primary text-F0F1F7' : '' }}" 
               href="#" data-bs-toggle="collapse" data-bs-target="#documentDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Document</span>
            </a>
            <ul class="collapse" id="documentDropdown">
                <li class="sidebar-list-item py-2 fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Document/DocumentRequest') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('document.documentStatus') }}">
                        Document Request
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('Document/DocumentType') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('document.documentType') }}">
                        Document Type
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('hrdocuments?0') ? 'bg-primary text-white' : '' }}" href="{{ route('admin.pages.hrdocuments.index', '0') }}">
                        Documents
                    </a>
                </li>
            </ul>
        </li>

        {{--INCOMING DOCUMENT COPY--}}

        <li class="sidebar-list-item py-2">
            <a class="sidebar-link {{ request()->is('incoming/list', 'incoming/actions/list') ? 'bg-primary text-F0F1F7' : '' }}" 
               href="#" data-bs-toggle="collapse" data-bs-target="#incomingDocumentDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Incoming Documents</span>
            </a>
            <ul class="collapse" id="incomingDocumentDropdown">
                <li class="sidebar-list-item py-2 fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('incoming-docs/list') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('incoming.list') }}">
                        Incoming Document Copy
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('incoming/actions/list') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('incoming.action.list') }}">
                        Action Type
                    </a>
                </li>
            </ul>
        </li>

        {{-- Admin: Request Form Config --}}
        <li class="sidebar-list-item py-2">
            <a class="sidebar-link {{ request()->is('request/requestform/config', 'leave/leaveform/config') ? 'bg-primary text-F0F1F7' : '' }}" 
               href="#" data-bs-toggle="collapse" data-bs-target="#requestDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Request Form Config</span>
            </a>
            <ul class="collapse" id="requestDropdown">
                <li class="sidebar-list-item py-2 fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('request/requestform/config') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('request.config') }}">
                        Document Form
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('leave/leaveform/config') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('leave.config') }}">
                        Leave Form
                    </a>
                </li>
                <li class="sidebar-list-item fs-6">
                    <a class="sidebar-link ms-3 {{ request()->is('incoming-docs/form/config') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('incoming.config') }}">
                        Incoming Document Copy Form
                    </a>
                </li>
            </ul>
        </li>
        @endif

        {{-- Request Leave & Document --}}
        @if (Auth::check() && (Auth::user()->role == 'Employee' || Auth::user()->role == 'System Admin'))
        <li class="sidebar-list-item py-2">
            <a class="sidebar-link" href="#" data-bs-toggle="collapse" data-bs-target="#widgetsDropdown" role="button" aria-expanded="false">
                <span class="sidebar-link-title fs-5">Request Leave & Document</span>
            </a>
            <ul class="collapse" id="widgetsDropdown">
                <li class="sidebar-list-item py-2">
                    <a class="sidebar-link" href="{{ route('leave.documentForm', 0) }}">
                        Request Leave
                    </a>
                </li>
                <li class="sidebar-list-item py-2">
                    <a class="sidebar-link" href="{{ route('request.documentForm', 0) }}">
                        Request Document
                    </a>
                </li>

                <li class="sidebar-list-item py-2">
                    <a class="sidebar-link" href="{{ route('incoming.documentForm', 0) }}">
                        Request Incoming Document Copy
                    </a>
                </li>

                @if(Auth::user()->role == 'System Admin')
                <li class="sidebar-list-item py-2">
                    <a class="sidebar-link {{ request()->is('hrdocuments?0') ? 'bg-primary text-F0F1F7' : '' }}" 
                       href="{{ route('admin.pages.hrdocuments.index','0') }}">
                        Documents
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
     

    </ul>
</div>