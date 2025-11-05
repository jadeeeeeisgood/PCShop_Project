#!/usr/bin/env python3
import zipfile
import os
import time

def create_deployment_zip():
    print("Creating deployment ZIP with Unix-compatible paths...")
    
    zip_name = f"pc-shop-routing-fix-{int(time.time())}.zip"
    
    # Files and directories to exclude
    exclude_patterns = {
        '.git', 'node_modules', '.env', '.env.local', '.env.example',
        'storage/logs/', 'storage/app/', 'storage/framework/cache/',
        'storage/framework/sessions/', 'storage/framework/views/',
        'bootstrap/cache/', 'vendor/', '.DS_Store', 'Thumbs.db',
        '__pycache__', '*.pyc', '*.log', '.ebextensions/*.bak'
    }
    
    with zipfile.ZipFile(zip_name, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk('.'):
            # Skip excluded directories
            dirs[:] = [d for d in dirs if not any(
                d.startswith(pattern.rstrip('/')) 
                for pattern in exclude_patterns 
                if pattern.endswith('/')
            )]
            
            for file in files:
                file_path = os.path.join(root, file)
                
                # Skip excluded files
                if any(pattern in file_path or file.endswith(pattern.lstrip('*')) 
                       for pattern in exclude_patterns):
                    continue
                
                # Convert Windows paths to Unix paths
                unix_path = file_path.replace('\\', '/').lstrip('./')
                
                try:
                    zipf.write(file_path, unix_path)
                    print(f"Added: {unix_path}")
                except Exception as e:
                    print(f"Error adding {file_path}: {e}")
    
    print(f"\nZIP created: {zip_name}")
    return zip_name

if __name__ == "__main__":
    create_deployment_zip()