<!DOCTYPE html>
<html>
<head>
    <title>Test Color Picker</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <h1>Test Color Picker</h1>
    <p>User: {{ Auth::user()->name }}</p>
    <p>Plan: {{ Auth::user()->plan }}</p>
    <p>Has PRO Color Wheel: {{ Auth::user()->hasProColorWheel() ? 'Yes' : 'No' }}</p>
    
    <div style="max-width: 400px; margin: 20px;">
        <x-modern-color-picker 
            name="test_color"
            selectedColor="blue"
            themeKey="test"
        />
    </div>
</body>
</html> 