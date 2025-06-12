<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSET-Office - Staff Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .header {
            background-color: #2c5530;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-title {
            font-size: 14px;
            font-style: italic;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .page-title {
            font-size: 24px;
            font-weight: 400;
            color: #666;
            margin-bottom: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .profile-header {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #1976d2;
        }

        .profile-content {
            display: flex;
            gap: 30px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background-color: #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #999;
        }

        .profile-info {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }

        .phone-link {
            color: #1976d2;
            text-decoration: none;
        }

        .phone-link:hover {
            text-decoration: underline;
        }

        .edit-photo {
            color: #1976d2;
            font-size: 12px;
            cursor: pointer;
            margin-top: 10px;
        }

        .tabs {
            display: flex;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            font-weight: 500;
            border-right: 1px solid #eee;
        }

        .tab:last-child {
            border-right: none;
        }

        .tab.active {
            background-color: #4fc3f7;
            color: white;
        }

        .tab:not(.active):hover {
            background-color: #f8f9fa;
        }

        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-header {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #1976d2;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .controls-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .controls-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Updated Button Styles to match the first code */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #1e7e34;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background: #bd2130;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        select, input {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            font-size: 14px;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 14px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #1976d2;
            font-size: 20px;
            text-align: center;
        }

        .modal-content label {
            display: block;
            margin: 15px 0 5px 0;
            font-weight: 500;
            color: #333;
        }

        .modal-content input,
        .modal-content select,
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .modal-content input:focus,
        .modal-content select:focus,
        .modal-content textarea:focus {
            border-color: #4fc3f7;
            outline: none;
            box-shadow: 0 0 0 2px rgba(79, 195, 247, 0.2);
        }

        .modal-content .btn {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            font-size: 16px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-info {
                grid-template-columns: 1fr;
            }
            
            .profile-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <span>🏢 MSET-Office</span>
            <span class="header-title">"Performance through People"</span>
        </div>
        <div class="user-info">
            <span>👤 #</span>
            <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">🚪 Logout</button>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <h1 class="page-title">Update Profile</h1>
            
            <div class="profile-card">
                <div class="profile-header">STAFF INFORMATION</div>
                <div class="profile-content">
                    <div>
                        <div class="profile-avatar">👤</div>
                        <div class="edit-photo">Delete Photo</div>
                    </div>
                    <div class="profile-info">
                        <div class="info-item">
                            <span class="info-label">Staff No :</span>
                            <span class="info-value">010426110178</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Role :</span>
                            <span class="info-value">INTERN</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Branch Code :</span>
                            <span class="info-value">AIMS GROUP</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Name :</span>
                            <span class="info-value">NURUL ATHIRA BINTI ZULKIFLI</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Start Date :</span>
                            <span class="info-value">2025-03-09</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Branch/Unit :</span>
                            <span class="info-value">INFORMATION TECHNOLOGY</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone No :</span>
                            <a href="tel:+60132364711" class="info-value phone-link">📱 +60132364711</a>
                        </div>
                        <div class="info-item">
                            <span class="info-label">End Date :</span>
                            <span class="info-value">1</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Department :</span>
                            <span class="info-value">AIMS-GLOBAL INTEGRITY RESOURCES SDN BHD</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status :</span>
                            <span class="info-value">WORKING</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="tab active" data-tab="education">Education Information</div>
                <div class="tab" data-tab="work">Previous Work Information</div>
                <div class="tab" data-tab="skills">Skills Information</div>
                <div class="tab" data-tab="family">Family Information</div>
            </div>

            <div class="content-section">
                <div class="section-header" id="sectionHeader">EDUCATION INFORMATION</div>
                
                <div class="controls">
                    <div class="controls-left">
                        <span>Show</span>
                        <select>
                            <option>5</option>
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span>records</span>
                    </div>
                    <div class="controls-right">
                        <button class="btn btn-success" id="addNewBtn">Add New</button>
                    </div>
                </div>

                <div class="controls">
                    <div></div>
                    <div class="controls-right">
                        <span>Search:</span>
                        <input type="text" placeholder="Search...">
                    </div>
                </div>

                <div id="tableContainer">
                    <!-- Tables will be dynamically updated based on active tab -->
                </div>

                <div class="pagination">
                    <span>Showing 0 to 0 of 0 records</span>
                    <div>
                        <button class="btn btn-secondary">Previous</button>
                        <button class="btn btn-secondary">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for pop-ups -->
    <div class="modal" id="educationModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Education</h3>
            <form id="educationForm">
                <label>Achievement</label>
                <input type="text" name="achievement" required>
                <label>Institution</label>
                <input type="text" name="institution" required>
                <label>Start Year</label>
                <input type="number" name="startYear" required>
                <label>End Year</label>
                <input type="number" name="endYear" required>
                <label>Result</label>
                <input type="text" name="result">
                <button type="submit" class="btn btn-primary">Add Education</button>
            </form>
        </div>
    </div>

    <div class="modal" id="employmentModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Employment</h3>
            <form id="employmentForm">
                <label>Workplace</label>
                <input type="text" name="workplace">
                <label>Position</label>
                <input type="text" name="position">
                <label>Role</label>
                <input type="text" name="role">
                <label>Start Date</label>
                <input type="date" name="startDate">
                <label>End Date</label>
                <input type="date" name="endDate">
                <label>Salary</label>
                <input type="number" name="salary" step="0.01">
                <button type="submit" class="btn btn-primary">Add Employment</button>
            </form>
        </div>
    </div>

    <div class="modal" id="skillModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Skill</h3>
            <form id="skillForm">
                <label>Skill</label>
                <input type="text" name="skill" required>
                <label>Level</label>
                <select name="level" required>
                    <option value="">Select Level</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Expert">Expert</option>
                </select>
                <button type="submit" class="btn btn-primary">Add Skill</button>
            </form>
        </div>
    </div>

    <div class="modal" id="familyModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Add Family Member</h3>
            <form id="familyForm">
                <label>Name</label>
                <input type="text" name="name" required>
                <label>Occupation</label>
                <input type="text" name="occupation">
                <label>Relationship</label>
                <select name="relationship" required>
                    <option value="">Select Relationship</option>
                    <option value="Father">Father</option>
                    <option value="Mother">Mother</option>
                    <option value="Spouse">Spouse</option>
                    <option value="Child">Child</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Other">Other</option>
                </select>
                <label>Phone</label>
                <input type="tel" name="phone">
                <button type="submit" class="btn btn-primary">Add Family</button>
            </form>
        </div>
    </div>

    <script>
        // Data storage
        const data = {
            education: [],
            work: [],
            skills: [],
            family: []
        };

        // Table configurations
        const tableConfigs = {
            education: {
                headers: ['#', 'Achievement', 'Institution', 'Start Year', 'End Year', 'Result', 'Action'],
                fields: ['achievement', 'institution', 'startYear', 'endYear', 'result']
            },
            work: {
                headers: ['#', 'Workplace', 'Position', 'Role', 'Start Date', 'End Date', 'Salary', 'Action'],
                fields: ['workplace', 'position', 'role', 'startDate', 'endDate', 'salary']
            },
            skills: {
                headers: ['#', 'Skill', 'Level', 'Action'],
                fields: ['skill', 'level']
            },
            family: {
                headers: ['#', 'Name', 'Occupation', 'Relationship', 'Phone', 'Action'],
                fields: ['name', 'occupation', 'relationship', 'phone']
            }
        };

        let currentTab = 'education';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateTable();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Tab switching
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    currentTab = this.dataset.tab;
                    updateSectionHeader();
                    updateTable();
                });
            });

            // Add new button
            document.getElementById('addNewBtn').addEventListener('click', function() {
                const modalId = currentTab === 'work' ? 'employmentModal' : 
                               currentTab === 'skills' ? 'skillModal' : 
                               currentTab + 'Modal';
                openModal(modalId);
            });

            // Form submissions
            document.getElementById('educationForm').addEventListener('submit', handleFormSubmit);
            document.getElementById('employmentForm').addEventListener('submit', handleFormSubmit);
            document.getElementById('skillForm').addEventListener('submit', handleFormSubmit);
            document.getElementById('familyForm').addEventListener('submit', handleFormSubmit);

            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = 'none';
                }
            });

            // Close buttons
            document.querySelectorAll('.close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    this.closest('.modal').style.display = 'none';
                });
            });
        }

        function updateSectionHeader() {
            const headers = {
                education: 'EDUCATION INFORMATION',
                work: 'WORK EXPERIENCE INFORMATION',
                skills: 'SKILLS INFORMATION',
                family: 'FAMILY INFORMATION'
            };
            document.getElementById('sectionHeader').textContent = headers[currentTab];
        }

        function updateTable() {
            const config = tableConfigs[currentTab];
            const tableData = data[currentTab];
            
            let tableHTML = `
                <table class="table">
                    <thead>
                        <tr>
                            ${config.headers.map(header => `<th>${header}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
            `;

            if (tableData.length === 0) {
                tableHTML += `<tr><td colspan="${config.headers.length}" class="no-data">No data available</td></tr>`;
            } else {
                tableData.forEach((item, index) => {
                    tableHTML += `<tr>`;
                    tableHTML += `<td>${index + 1}</td>`;
                    config.fields.forEach(field => {
                        tableHTML += `<td>${item[field] || ''}</td>`;
                    });
                    tableHTML += `<td>
                        <button class="btn btn-primary btn-sm" onclick="editItem('${currentTab}', ${index})" style="margin-right: 5px;">Edit</button>
                        <button class="btn btn-cancel btn-sm" onclick="deleteItem('${currentTab}', ${index})">Delete</button>
                    </td>`;
                    tableHTML += `</tr>`;
                });
            }

            tableHTML += `
                    </tbody>
                </table>
            `;

            document.getElementById('tableContainer').innerHTML = tableHTML;
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function handleFormSubmit(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const formObject = {};
            
            for (let [key, value] of formData.entries()) {
                formObject[key] = value;
            }

            // Determine which data array to update based on form
            let dataType;
            if (event.target.id === 'educationForm') dataType = 'education';
            else if (event.target.id === 'employmentForm') dataType = 'work';
            else if (event.target.id === 'skillForm') dataType = 'skills';
            else if (event.target.id === 'familyForm') dataType = 'family';

            data[dataType].push(formObject);
            
            // Close modal and update table
            const modalId = dataType === 'work' ? 'employmentModal' : 
                           dataType === 'skills' ? 'skillModal' : 
                           dataType + 'Modal';
            closeModal(modalId);
            event.target.reset();
            
            if (currentTab === dataType) {
                updateTable();
            }

            alert(`${dataType.charAt(0).toUpperCase() + dataType.slice(1)} added successfully!`);
        }

        function editItem(type, index) {
            alert(`Edit functionality for ${type} item ${index + 1} would be implemented here`);
        }

        function deleteItem(type, index) {
            if (confirm('Are you sure you want to delete this item?')) {
                data[type].splice(index, 1);
                if (currentTab === type) {
                    updateTable();
                }
                alert('Item deleted successfully!');
            }
        }

        // Search functionality
        document.querySelector('input[type="text"]').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            console.log('Searching for:', searchTerm);
            // 
        });
    </script>
</body>
</html>