import os
import re

files_to_fix = [
    'resources/views/card/theme10/index.blade.php',
    'resources/views/card/theme11/index.blade.php',
    'resources/views/card/theme12/index.blade.php',
    'resources/views/card/theme13/index.blade.php',
    'resources/views/card/theme14/index.blade.php',
    'resources/views/card/theme15/index.blade.php',
    'resources/views/card/theme16/index.blade.php',
    'resources/views/card/theme17/index.blade.php',
    'resources/views/card/theme18/index.blade.php',
    'resources/views/card/theme20/index.blade.php',
    'resources/views/card/theme21/index.blade.php',
    'resources/views/card/theme4/index.blade.php',
    'resources/views/card/theme6/index.blade.php',
    'resources/views/card/theme7/index.blade.php',
    'resources/views/card/theme8/index.blade.php'
]

# Pattern:
# @endif (closes isDesktop)
# @endif (WRONG ONE)
# @else (for if Whatsapp)

# We want to remove the middle @endif.

# Regex explanation:
# (\s*@endif)  --> Group 1: The first endif (keep)
# (\s*@endif)  --> Group 2: The second endif (remove)
# (\s*@else)   --> Group 3: The else (keep)

pattern = re.compile(r'(\s*@endif)(\s*@endif)(\s*@else)', re.MULTILINE)

for file_path in files_to_fix:
    if not os.path.exists(file_path):
        print(f"File not found: {file_path}")
        continue

    with open(file_path, 'r') as f:
        content = f.read()

    # Check if pattern exists
    if pattern.search(content):
        new_content = pattern.sub(r'\1\3', content)
        with open(file_path, 'w') as f:
            f.write(new_content)
        print(f"Fixed {file_path}")
    else:
        print(f"Pattern not found in {file_path}")
