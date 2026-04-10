import paramiko
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
channel.exec_command("""
echo '=== APP_URL ===' && grep APP_URL /var/www/portfolio/.env && \
echo '=== STORAGE LINK ===' && ls -la /var/www/portfolio/public/storage && \
echo '=== STORAGE DIRS ===' && ls /var/www/portfolio/storage/app/public/ && \
echo '=== PHP UPLOAD ===' && php -r "echo ini_get('upload_max_filesize').' / '.ini_get('post_max_size');" && \
echo '' && echo '=== NGINX BODY ===' && grep -r client_max_body /etc/nginx/ 2>/dev/null | head -3 && \
echo '=== PROXY MIDDLEWARE ===' && cat /var/www/portfolio/app/Http/Middleware/TrustProxies.php 2>/dev/null | head -30 && \
echo '=== LIVEWIRE CONFIG ===' && cat /var/www/portfolio/config/livewire.php 2>/dev/null | head -5 || echo 'no config' && \
echo '=== DONE ==='
""")

output = b''
while True:
    try:
        data = channel.recv(8192)
        if not data:
            break
        output += data
    except:
        break

print(output.decode('utf-8', errors='replace'))
ssh.close()
