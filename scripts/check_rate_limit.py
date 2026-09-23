import os, re

d = r'd:\src_code\Py\Portfolio\portfolio-lv\app\Livewire\Admin'
rl = re.compile(r'RateLimiter::')

for f in os.listdir(d):
    if not f.endswith('.php'): continue
    path = os.path.join(d, f)
    with open(path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    parts = re.split(r'public function ', content)[1:]
    for p in parts:
        name = p.split('(', 1)[0].strip()
        if re.match(r'^(save|delete|update|store|create|add|remove|toggle|edit)[a-zA-Z0-9_]*$', name):
            if not rl.search(p):
                print(f'{f}: {name} is MISSING RateLimiter')
