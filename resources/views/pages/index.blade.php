<!DOCTYPE html>
<html>
<head>
    <title>Pages List</title>
</head>
<body>
    <h1>Pages</h1>
    <a href="{{ route('admin.pages.create') }}">Create New Page</a>
    <ul>
        @foreach ($pages as $page)
            <li>
                <strong>Admin Landing:</strong> {{ json_encode($page->admin_landing) }}<br>
                <strong>Delivery Partner:</strong> {{ json_encode($page->delivery_partner) }}<br>
                <a href="{{ route('pages.edit', $page->id) }}">Edit</a>
                <form action="{{ route('pages.destroy', $page->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
