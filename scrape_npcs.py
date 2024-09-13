import cloudscraper
from bs4 import BeautifulSoup
import time
import csv
import os
import re

# Create a cloudscraper session to handle Cloudflare
scraper = cloudscraper.create_scraper()

# Base URLs
npc_base_url = "https://everythingrs.com/tools/osrs/npclist/"
item_base_url = "https://everythingrs.com/tools/osrs/itemlist/"

directory = "storage/app/scrape/"

# Progress files to keep track of last successfully scraped page
npc_progress_file = directory + 'npc_scrape_progress.txt'
item_progress_file = directory + 'item_scrape_progress.txt'

# CSV files for storing data
npc_csv_file = directory + 'osrs_npcs.csv'
item_csv_file = directory + 'osrs_items.csv'

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
    if not isinstance(name, str):
        return False
    if not isinstance(data_id, int):
        return False
    # Simple regex to check if the URL is valid (must start with http/https and have a domain)
    url_pattern = re.compile(r'https?://[^\s]+')
    if not re.match(url_pattern, img_url):
        return False
    return True

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
                scraped_data.append({
                    'Name': name,
                    'ID': npc_id,
                    'Image URL': img_url
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
                scraped_data.append({
                    'Name': name,
                    'ID': item_id,
                    'Image URL': img_url
                })

    return scraped_data

# Function to scrape all NPC pages
def scrape_all_npc_pages():
    page_num = load_last_page(npc_progress_file)  # Start from the last saved page
    all_npc_data = []

    # If CSV does not exist, create it and write headers
    if not os.path.exists(npc_csv_file):
        with open(npc_csv_file, 'w', newline='', encoding='utf-8') as csvfile:
            fieldnames = ['Name', 'ID', 'Image URL']
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
            writer.writeheader()

    while True:
        print(f"Scraping NPC page {page_num}...")
        try:
            data = scrape_npc_page(page_num)
            if not data:  # If no data is returned, exit loop
                print(f"Finished scraping NPCs up to page {page_num - 1}.")
                break

            # Append the new data to the CSV
            with open(npc_csv_file, 'a', newline='', encoding='utf-8') as csvfile:
                fieldnames = ['Name', 'ID', 'Image URL']
                writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
                writer.writerows(data)

            # Save progress after each page
            save_progress(page_num, npc_progress_file)

            # Increment the page number
            page_num += 1

            # Add a delay between requests to avoid overloading the server
            time.sleep(0)

        except Exception as e:
            print(f"An error occurred on NPC page {page_num}: {e}")
            break

    print("NPC scraping completed.")

# Function to scrape all item pages
def scrape_all_item_pages():
    page_num = load_last_page(item_progress_file)  # Start from the last saved page
    all_item_data = []

    # If CSV does not exist, create it and write headers
    if not os.path.exists(item_csv_file):
        with open(item_csv_file, 'w', newline='', encoding='utf-8') as csvfile:
            fieldnames = ['Name', 'ID', 'Image URL']
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
            writer.writeheader()

    while True:
        print(f"Scraping item page {page_num}...")
        try:
            data = scrape_item_page(page_num)
            if not data:  # If no data is returned, exit loop
                print(f"Finished scraping items up to page {page_num - 1}.")
                break

            # Append the new data to the CSV
            with open(item_csv_file, 'a', newline='', encoding='utf-8') as csvfile:
                fieldnames = ['Name', 'ID', 'Image URL']
                writer = csv.DictWriter(csvfile, fieldnames=fieldnames, escapechar='\\', quoting=csv.QUOTE_MINIMAL)
                writer.writerows(data)

            # Save progress after each page
            save_progress(page_num, item_progress_file)

            # Increment the page number
            page_num += 1

            # Add a delay between requests to avoid overloading the server
            time.sleep(0)

        except Exception as e:
            print(f"An error occurred on item page {page_num}: {e}")
            break

    print("Item scraping completed.")

# Start scraping NPCs and items
scrape_all_npc_pages()
scrape_all_item_pages()
