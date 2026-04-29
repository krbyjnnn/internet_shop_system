<!DOCTYPE html>
<html>
<body>
    <h1>Customer Dashboard</h1>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>