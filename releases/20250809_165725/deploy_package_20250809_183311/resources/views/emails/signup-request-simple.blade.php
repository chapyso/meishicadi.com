<!DOCTYPE html>
<html>
<head>
    <title>New Signup Request</title>
</head>
<body>
    <h1>New Signup Request</h1>
    <p><strong>Name:</strong> {{ $name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $email ?? 'N/A' }}</p>
    <p><strong>Phone:</strong> {{ $phone ?? 'N/A' }}</p>
    <p><strong>Company:</strong> {{ $company ?? 'N/A' }}</p>
    <p><strong>Cards Required:</strong> {{ $cards_required ?? 'N/A' }}</p>
    @if(!empty($industry))
        <p><strong>Industry:</strong> {{ $industry }}</p>
    @endif
    @if(!empty($message))
        <p><strong>Message:</strong> {{ $message }}</p>
    @endif
    <p><strong>Submitted:</strong> {{ $submitted_at ?? 'N/A' }}</p>
</body>
</html>
