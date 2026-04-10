import os
import re

FILES = [
    r"resources\views\livewire\admin\manage-certificates.blade.php",
    r"resources\views\livewire\admin\manage-experiences.blade.php",
    r"resources\views\livewire\admin\manage-languages.blade.php",
    r"resources\views\livewire\admin\manage-posts.blade.php",
    r"resources\views\livewire\admin\manage-skills.blade.php",
    r"resources\views\livewire\admin\profile-settings.blade.php"
]

BASE_DIR = r"d:\src_code\Py\Portfolio\portfolio-lv"

def fix_file(filepath):
    full_path = os.path.join(BASE_DIR, filepath)
    if not os.path.exists(full_path):
        print(f"File not found: {full_path}")
        return

    with open(full_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Pattern 1: Main Modal Backdrop
    # Look for bg-black/95 or 90 or 80, optionally followed by backdrop-blur-sm
    # Replace with bg-black/50 backdrop-blur-md
    
    # Simple replace for specific known patterns first
    content = content.replace('bg-black/95 backdrop-blur-sm', 'bg-black/50 backdrop-blur-md')
    content = content.replace('bg-black/90 backdrop-blur-sm', 'bg-black/50 backdrop-blur-md')
    content = content.replace('bg-black/80 backdrop-blur-sm', 'bg-black/50 backdrop-blur-md')
    
    # Delete Modal / Other Modals often have just bg-black/80
    # We want to catch class="..." containing bg-black/80 and make sure it has backdrop-blur-md
    # Use regex for this to be safe
    
    # Regex to find bg-black/XX and replace with bg-black/50
    # And ensuring backdrop-blur-md is present
    
    def replace_backdrop(match):
        # Determine strictness. If it's a backdrop div, it usually has fixed inset-0
        full_match = match.group(0)
        if 'fixed' in full_match and 'inset-0' in full_match:
            # It's likely a backdrop
            new_class = full_match
            # Replace opacity
            new_class = re.sub(r'bg-black/\d+', 'bg-black/50', new_class)
            # Handle blur
            if 'backdrop-blur-' in new_class:
                new_class = re.sub(r'backdrop-blur-\w+', 'backdrop-blur-md', new_class)
            else:
                new_class = new_class.replace('bg-black/50', 'bg-black/50 backdrop-blur-md')
            return new_class
        return full_match

    # Search for class attributes containing bg-black/
    content = re.sub(r'class="[^"]*bg-black/\d+[^"]*"', replace_backdrop, content)

    with open(full_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {filepath}")

def main():
    for file in FILES:
        fix_file(file)

if __name__ == "__main__":
    main()
