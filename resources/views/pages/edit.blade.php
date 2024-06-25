<!DOCTYPE html>
<html>
<head>
    <title>Edit Page</title>
</head>
<body>
    <h1>Edit Page</h1>
    <form action="{{ route('pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="admin_landing">Admin Landing:</label>
        <input type="text" name="admin_landing" id="admin_landing" value="{{ json_encode($page->admin_landing) }}"><br>
        <label for="delivery_partner">Delivery Partner:</label>
        <input type="text" name="delivery_partner" id="delivery_partner" value="{{ json_encode($page->delivery_partner) }}"><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
