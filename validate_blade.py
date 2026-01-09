import re
import sys

def validate(file_path):
    print(f"Validating {file_path}...")
    with open(file_path, 'r') as f:
        lines = f.readlines()

    stack = []
    
    # Simple regex for directives
    # We care about @if, @foreach, @for, @while, @push, @section (maybe), @auth, @guest, @can
    # And their end counterparts.
    
    # Key: directive -> close_directive
    pairs = {
        '@if': '@endif',
        '@foreach': '@endforeach',
        '@for': '@endfor',
        '@while': '@endwhile',
        '@auth': '@endauth',
        '@guest': '@endguest',
        '@can': '@endcan',
        '@isset': '@endisset',
        '@empty': '@endempty',
        '@error': '@enderror',
        '@unless': '@endunless',
        '@switch': '@endswitch',
        '@hassection': '@endif', # Special case? Check laravel docs. It's @endif.
        '@section': '@endsection', # Or @stop, @show
        '@push': '@endpush',
        '@prepend': '@endprepend',
        '@slot': '@endslot',
        '@component': '@endcomponent',
    }
    
    # Regex to find directives
    # \B@\w+ matches @if but not email@address
    regex = re.compile(r'(\B@\w+)')

    # Ignore blade comments handled by logic not regex? No, blade comments {{-- --}} can hide text.
    # We should strip comments first.
    
    full_text = "".join(lines)
    # Strip comments {{-- ... --}}
    full_text = re.sub(r'\{\{--.*?--\}\}', '', full_text, flags=re.DOTALL)
    
    # Parse line by line from stripped text is hard because line numbers updates.
    # We will just parse the lines and ignore comment lines crudely.
    
    for i, line in enumerate(lines):
        line_num = i + 1
        original_line = line
        
        # Remove {{-- --}} comments on the same line
        line = re.sub(r'\{\{--.*?--\}\}', '', line)
        
        # Find all directives in syntax
        matches = regex.finditer(line)
        for match in matches:
            token = match.group(1)
            
            # Map synonyms
            if token == '@elseif' or token == '@else':
                if not stack:
                    print(f"Error line {line_num}: {token} without start")
                    continue
                # Peek stack, MUST be @if, @isset, @empty, @auth, @guest, @can, @switch
                # Actually, @foreach doesn't have else.
                top = stack[-1]
                if top not in ['@if', '@isset', '@empty', '@auth', '@guest', '@can', '@switch', '@unless']:
                     print(f"Error line {line_num}: {token} after {top}")
                continue

            if token in pairs:
                stack.append(token)
            elif token in map(lambda x: x if x != '@endif' else '@endif', pairs.values()): # Check closing
                 # Special handling for @endif which closes many things
                 if token == '@endif':
                     if not stack:
                         print(f"Error line {line_num}: Unexpected @endif")
                         return
                     top = stack.pop()
                     valid_openers = ['@if', '@isset', '@empty', '@hassection', '@auth', '@guest', '@can', '@unless']
                     if top not in valid_openers:
                           print(f"Error line {line_num}: @endif matched with {top}")
                 elif token == '@endsection':
                     if stack and stack[-1] == '@section':
                         stack.pop()
                     else:
                         # @section can be closed by @show or @stop too?
                         print(f"Error line {line_num}: Unexpected @endsection")
                 else:
                     # General case
                     if not stack:
                          print(f"Error line {line_num}: Unexpected {token}")
                          return
                     top = stack.pop()
                     expected = pairs.get(top)
                     if expected != token:
                          print(f"Error line {line_num}: Expected {expected} but found {token} (matches {top})")

    if stack:
        print(f"Error: Stack not empty at EOF. Open blocks: {stack}")
    else:
        print("Validation Successful")

if __name__ == '__main__':
    if len(sys.argv) > 1:
        validate(sys.argv[1])
    else:
        print("Usage: python3 validate_blade.py <file_path>")
