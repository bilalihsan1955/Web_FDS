import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

def extract_all_text(file_path):
    print(f"\n======================================")
    print(f"ALL TEXT CONTENT IN: {file_path}")
    print(f"======================================")
    with open(file_path, 'r', encoding='utf-8') as f:
        html = f.read()
    
    # Strip scripts & styles
    html_clean = re.sub(r'<script.*?>.*?</script>', '', html, flags=re.DOTALL)
    html_clean = re.sub(r'<style.*?>.*? </style>', '', html_clean, flags=re.DOTALL)
    
    # Find all text inside tags
    # Let's extract any HTML block content between tags and strip whitespace
    tags_content = re.findall(r'>([^<]+)<', html_clean)
    clean_lines = []
    for line in tags_content:
        line_clean = line.strip()
        # Decode common HTML entities
        line_clean = line_clean.replace('&nbsp;', ' ').replace('&#8211;', '-').replace('&amp;', '&').replace('&mdash;', '—')
        if len(line_clean) > 20 and not any(x in line_clean for x in ['window.', 'jQuery', 'fbq', 'function(']):
            clean_lines.append(line_clean)
            
    # Print lines
    for idx, line in enumerate(clean_lines[:100]): # print top 100 lines
        print(f"{idx+1}: {line}")

extract_all_text(r"C:\Users\BILALIHSAN\.gemini\antigravity-ide\brain\81787000-58bb-4154-99f8-5d3e070d7378\.system_generated\steps\1143\content.md")
extract_all_text(r"C:\Users\BILALIHSAN\.gemini\antigravity-ide\brain\81787000-58bb-4154-99f8-5d3e070d7378\.system_generated\steps\1145\content.md")
extract_all_text(r"C:\Users\BILALIHSAN\.gemini\antigravity-ide\brain\81787000-58bb-4154-99f8-5d3e070d7378\.system_generated\steps\1147\content.md")
