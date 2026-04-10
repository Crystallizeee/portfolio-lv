import paramiko
import re
from config import HOST, USERNAME, PASSWORD, REMOTE_DIR
SERVER = HOST
USER = USERNAME
GATEWAY_IP = HOST

APP_DIR = "/var/www/portfolio"

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    try:
        ssh.connect(HOST, username=USERNAME, password=PASSWORD)
        
        # 1. Get the CSS filename from manifest
        print("--- Reading Manifest ---")
        stdin, stdout, stderr = ssh.exec_command(f"cat {APP_DIR}/public/build/manifest.json")
        manifest = stdout.read().decode()
        print(manifest)
        
        # Extract CSS file path
        # Simple regex or string find, assuming standard vite manifest structure
        match = re.search(r'"file":\s*"(assets/app-[\w-]+\.css)"', manifest)
        if match:
            css_file = match.group(1)
            print(f"CSS File: {css_file}")
            
            # 2. Check if file exists
            print(f"--- Checking File: public/build/{css_file} ---")
            ssh.exec_command(f"ls -l {APP_DIR}/public/build/{css_file}")
            
            # 3. Try to Curl it via localhost (HTTP)
            url = f"http://localhost/build/{css_file}"
            print(f"--- Curling {url} ---")
            stdin, stdout, stderr = ssh.exec_command(f"curl -I {url}")
            print(stdout.read().decode())
        else:
            print("Could not find CSS file in manifest")

        # 4. Check Nginx Error Log for static file issues
        print("--- Nginx Asset Errors ---")
        stdin, stdout, stderr = ssh.exec_command("grep 'assets' /var/log/nginx/error.log | tail -n 10")
        print(stdout.read().decode())
        
        # 5. Check permissions of public directory again
        print("--- Public Dir Permissions ---")
        ssh.exec_command(f"ls -la {APP_DIR}/public/build")

    except Exception as e:
        print(e)
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
