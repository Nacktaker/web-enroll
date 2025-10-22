<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>สร้างบัญชี</title>
</head>
<body>
    <h1>สร้างบัญชี</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" id="create-user-form">
        @csrf

        <div>
            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
        </div>

        <div>
            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div>
            <span>Role</span>
            <label><input type="radio" name="role" value="student" checked> Student</label>
            <label><input type="radio" name="role" value="teacher"> Teacher</label>
            <label><input type="radio" name="role" value="admin"> Admin</label>
        </div>

        <div id="student-fields">
            <h3>Student details</h3>
            <div>
                <label for="stu_code">Student Code</label>
                <input type="text" id="stu_code" name="stu_code" value="{{ old('stu_code') }}">
            </div>
            <div>
                <label for="faculty">Faculty</label>
                <input type="text" id="faculty" name="faculty" value="{{ old('faculty') }}">
            </div>
            <div>
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="{{ old('department') }}">
            </div>
            <div>
                <label for="year">Year</label>
                <input type="text" id="year" name="year" value="{{ old('year') }}">
            </div>
        </div>

        <div id="teacher-fields" style="display:none;">
            <h3>Teacher details</h3>
            <div>
                <label for="teacher_code">Teacher Code</label>
                <input type="text" id="teacher_code" name="teacher_code" value="{{ old('teacher_code') }}">
            </div>
            <div>
                <label for="teacher_faculty">Faculty</label>
                <input type="text" id="teacher_faculty" name="teacher_faculty" value="{{ old('teacher_faculty') }}">
            </div>
        </div>

        <div id="admin-fields" style="display:none;">
            <h3>Admin details</h3>
            <div>
                <label for="admin_code">Admin Code</label>
                <input type="text" id="admin_code" name="admin_code" value="{{ old('admin_code') }}">
            </div>
            <div>
                <label for="admin_unit">Unit/Department</label>
                <input type="text" id="admin_unit" name="admin_unit" value="{{ old('admin_unit') }}">
            </div>
        </div>

        <div style="margin-top:12px;">
            <button type="submit">สร้าง</button>
        </div>
    </form>

    <script>
        function updateVisibility() {
            var role = document.querySelector('input[name="role"]:checked').value;
                document.getElementById('student-fields').style.display = role === 'student' ? 'block' : 'none';
                document.getElementById('teacher-fields').style.display = role === 'teacher' ? 'block' : 'none';
                document.getElementById('admin-fields').style.display = role === 'admin' ? 'block' : 'none';
        }

        var radios = document.querySelectorAll('input[name="role"]');
        radios.forEach(function(r) { r.addEventListener('change', updateVisibility); });
        updateVisibility();

        // Form now posts firstname and lastname directly; no hidden name field needed.
    </script>

</body>
</html>
