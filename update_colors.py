import os

files_to_check = [
    'home.blade.php',
    'layouts/main.blade.php',
    'layouts/admin_menu.blade.php',
    'layouts/adminmhs_menu.blade.php',
    'layouts/dosen_menu.blade.php',
    'layouts/pembimbing_luar_menu.blade.php',
]

base_dir = '/Users/andika/Documents/GitHub/sinergi-markandeya/resources/views/'

replacements = {
    'blue-': 'primary-',
    'indigo-': 'primary-',
    'emerald-': 'gold-',
    'amber-': 'gold-',
    'purple-': 'primary-',
    'slate-': 'gray-',
}

for filename in files_to_check:
    filepath = os.path.join(base_dir, filename)
    if not os.path.exists(filepath):
        continue
        
    with open(filepath, 'r') as f:
        content = f.read()
    
    for old, new in replacements.items():
        content = content.replace(old, new)
        
    with open(filepath, 'w') as f:
        f.write(content)
        
print("Colors updated successfully.")
