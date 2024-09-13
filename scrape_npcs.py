import cloudscraper
from bs4 import BeautifulSoup
import time
import csv
import os
import re
import requests
from concurrent.futures import ThreadPoolExecutor, as_completed

# Create a cloudscraper session to handle Cloudflare
scraper = cloudscraper.create_scraper()

# Base URLs
npc_base_url = "https://everythingrs.com/tools/osrs/npclist/"
item_base_url = "https://everythingrs.com/tools/osrs/itemlist/"

# Directory paths for storing images and CSV files
npc_image_dir = "storage/app/scrape/npc_images/"
item_image_dir = "storage/app/scrape/item_images/"
npc_progress_file = npc_image_dir + 'npc_scrape_progress.txt'
item_progress_file = item_image_dir + 'item_scrape_progress.txt'
npc_csv_file = npc_image_dir + 'osrs_npcs.csv'
item_csv_file = item_image_dir + 'osrs_items.csv'

# Ensure directories exist
os.makedirs(npc_image_dir, exist_ok=True)
os.makedirs(item_image_dir, exist_ok=True)

# Function to load the last saved page number for scraping
def load_last_page(progress_file):
    if os.path.exists(progress_file):
        with open(progress_file, 'r') as file:
            return int(file.read().strip())
    return 1  # Start from page 1 if no progress file exists

# Function to save the current page number for scraping
def save_progress(page_num, progress_file):
    with open(progress_file, 'w') as file:
        file.write(str(page_num))

# Function to verify if data is in the correct format
def verify_data(name, data_id, img_url):
    if not name or not isinstance(name, str):
        return False
    if not isinstance(data_id, int):
        return False
    # Simple regex to check if the URL is valid (must start with http/https and have a domain)
    url_pattern = re.compile(r'https?://[^\s]+')
    if not re.match(url_pattern, img_url):
        return False
    return True

# Function to validate if an image URL is a valid image
def is_valid_image(image_url):
    try:
        response = scraper.head(image_url, timeout=10)
        content_type = response.headers.get('Content-Type')
        if response.status_code == 200 and 'image' in content_type:
            return True
    except Exception as e:
        print(f"Error validating image URL {image_url}: {e}")
    return False

# Function to download the image and return the filename
def download_image(image_url, save_dir, prefix, data_id):
    if not is_valid_image(image_url):
        print(f"Skipping invalid image: {image_url}")
        return None

    img_extension = os.path.splitext(image_url)[-1]
    img_filename = f"{prefix}_{data_id}{img_extension}"
    img_path = os.path.join(save_dir, img_filename)
    try:
        img_data = scraper.get(image_url).content
        with open(img_path, 'wb') as img_file:
            img_file.write(img_data)
        return img_filename
    except Exception as e:
        print(f"Failed to download image {image_url}: {e}")
        return None

# Function to scrape a page and return the NPC data
def scrape_npc_page(page_num):
    url = npc_base_url + str(page_num)
    response = scraper.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')

    # Find the NPC table
    table = soup.find('table', {'class': 'table table-striped table-bordered table-list'})
    if not table:
        return None  # Return None if no table is found (last page or invalid page)

    # Extract data from the table rows
    rows = table.find_all('tr')
    scraped_data = []

    for row in rows:
        cols = row.find_all('td')
        if len(cols) > 1:  # Only process rows with valid data
            img_tag = cols[0].find('img')['src'] if cols[0].find('img') else None
            name = cols[1].text.strip()
            npc_id = cols[2].text.strip()

            # Ensure the npc_id is an integer before verification
            try:
                npc_id = int(npc_id)
            except ValueError:
                continue  # Skip this row if the npc_id is not an integer

            img_url = img_tag

            # Verify data before adding it
            if verify_data(name, npc_id, img_url):
                img_filename = download_image(img_url, npc_image_dir, "npc", npc_id)
                if img_filename:  # Only append if image download was successful
                    scraped_data.append({
                        'Name': name,
                        'ID': npc_id,
                        'Image Filename': img_filename
                    })

    return scraped_data

# Function to scrape a page and return the item data
def scrape_item_page(page_num):
    url = item_base_url + str(page_num)
    response = scraper.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')

    # Find the item table
    table = soup.find('table', {'class': 'table table-striped table-bordered table-list'})
    if not table:
        return None  # Return None if no table is found (last page or invalid page)

    # Extract data from the table rows
    rows = table.find_all('tr')
    scraped_data = []

    for row in rows:
        cols = row.find_all('td')
        if len(cols) > 1:  # Only process rows with valid data
            img_tag = cols[0].find('img')['src'] if cols[0].find('img') else None
            name = cols[1].text.strip()
            item_id = cols[2].text.strip()

            # Ensure the item_id is an integer before verification
            try:
                item_id = int(item_id)
            except ValueError:
                continue  # Skip this row if the item_id is not an integer

            img_url = img_tag

            # Verify data before adding it
            if verify_data(name, item_id, img_url):
                img_filename = download_image(img_url, item_image_dir, "item", item_id)
                if img_filename:  # Only append if image download was successful
                    scraped_data.append({
                        'Name': name,
                        'ID': item_id,
                        'Image Filename': img_filename
                    })

    return scraped_data

# Function to scrape all NPC pages in parallel using multithreading
def scrape_all_npc_pages():
    page_num = load_last_page(npc_progress_file)  # Start from the last saved page
    max_page_num = 100  # Set to a reasonable maximum page limit

    # If CSV does not exist, create it and write headers
    if not os.path.exists(npc_csv_file):
        with open(npc_csv_file, 'w', newline='', encoding='utf-8') as csvfile:
            fieldnames = ['Name', 'ID', 'Image Filename']
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
            writer.writeheader()

    with ThreadPoolExecutor(max_workers=10) as executor:
        futures = [executor.submit(scrape_npc_page, page) for page in range(page_num, max_page_num)]
        
        for future in as_completed(futures):
            try:
                data = future.result()
                if data:
                    # Append the new data to the CSV
                    with open(npc_csv_file, 'a', newline='', encoding='utf-8') as csvfile:
                        fieldnames = ['Name', 'ID', 'Image Filename']
                        writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
                        writer.writerows(data)
                    save_progress(page_num, npc_progress_file)
                    page_num += 1
            except Exception as e:
                print(f"An error occurred while scraping NPC data: {e}")

    print("NPC scraping completed.")

# Function to scrape all item pages in parallel using multithreading
def scrape_all_item_pages():
    page_num = load_last_page(item_progress_file)  # Start from the last saved page
    max_page_num = 500  # Set to a reasonable maximum page limit

    # If CSV does not exist, create it and write headers
    if not os.path.exists(item_csv_file):
        with open(item_csv_file, 'w', newline='', encoding='utf-8') as csvfile:
            fieldnames = ['Name', 'ID', 'Image Filename']
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
            writer.writeheader()

    with ThreadPoolExecutor(max_workers=10) as executor:
        futures = [executor.submit(scrape_item_page, page) for page in range(page_num, max_page_num)]

        for future in as_completed(futures):
            try:
                data = future.result()
                if data:
                    # Append the new data to the CSV
                    with open(item_csv_file, 'a', newline='', encoding='utf-8') as csvfile:
                        fieldnames = ['Name', 'ID', 'Image Filename']
                        writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
                        writer.writerows(data)
                    save_progress(page_num, item_progress_file)
                    page_num += 1
            except Exception as e:
                print(f"An error occurred while scraping item data: {e}")

    print("Item scraping completed.")

# Start scraping NPCs and items in parallel
scrape_all_npc_pages()
scrape_all_item_pages()
