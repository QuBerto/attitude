import requests
from bs4 import BeautifulSoup
import os
import re
import json
def search_osrs_wiki(search_term):
    base_url = "https://oldschool.runescape.wiki"
    search_url = f"{base_url}/w/Special:Search"
    
    params = {
        'search': search_term,
        'go': 'Go'
    }
    
    response = requests.get(search_url, params=params)
    
    if response.status_code != 200:
        print(f"Failed to search for {search_term}. Status code: {response.status_code}")
        return None

    soup = BeautifulSoup(response.content, 'html.parser')
    first_result = soup.find('ul', class_='mw-search-results')
    if first_result:
        first_link = first_result.find('a', href=True)
        if first_link:
            result_url = base_url + first_link['href']
            result_text = first_link.get_text(strip=True)
            return result_text, result_url
    else:
        print(f"No results found for {search_term}")
        return None

def get_search_terms_from_localhost():
    try:
        response = requests.get("http://localhost:8000/all")
        if response.status_code == 200:
            return response.json()
        else:
            print(f"Failed to get search terms. Status code: {response.status_code}")
            return None
    except requests.exceptions.RequestException as e:
        print(f"Error occurred: {e}")
        return None

def download_image(img_url, save_dir, image_name):
    try:
        img_data = requests.get(img_url).content
        file_path = os.path.join(save_dir, image_name)
        with open(file_path, 'wb') as handler:
            handler.write(img_data)
        print(f"Image downloaded and saved as {file_path}")
    except Exception as e:
        print(f"Failed to download image {img_url}. Error: {e}")

def extract_title_and_image(result_url):
    response = requests.get(result_url)
    if response.status_code != 200:
        print(f"Failed to retrieve the page. Status code: {response.status_code}")
        return None, None
    
    soup = BeautifulSoup(response.content, 'html.parser')
    
    # Extract the title of the page
    title = soup.find('h1', class_='firstHeading').get_text(strip=True)
    
    # First, find the parent element with class 'infobox-image'
    parent_element = soup.find('td', class_='infobox-full-width-content')

    if parent_element:
        # Then, find the 'img' tag inside the parent that has class 'mw-file-element'
        print(parent_element)
        image = parent_element.find('img', class_='mw-file-element')

        if image:
            img_url = 'https://oldschool.runescape.wiki' + image['src']  # Build the full image URL
            return title, img_url
        else:
            print("No 'img' found with class 'mw-file-element' inside 'infobox-image'.")
            return title, None
    else:
        print("No element found with class 'infobox-image'.")
        return title, None

def main():
    search_data = get_search_terms_from_localhost()
    
    if search_data and 'data' in search_data:
      
        save_dir = './images'  # Directory to save images
        os.makedirs(save_dir, exist_ok=True)  # Create the directory if it doesn't exist
        
        saved_data = []
        
        for row in search_data['data']:
            search_term = row['npc_slug']
            search_id = row['npc_id']

            if search_term:
                # Remove numbers and replace underscores with spaces
                search_term = re.sub(r'\d+', '', search_term)  # Remove numbers
                search_term = search_term.replace('_', ' ')    # Replace underscores with spaces
                
                print(f"Searching OSRS Wiki for: {search_term} (ID: {search_id})")
                result = search_osrs_wiki(search_term + " npc")
                if result:
                    result_text, result_url = result
                    print(f"Search result for '{search_term}': {result_text}")
                    print(f"  URL: {result_url}")
                    
                    # Extract the title and image from the result URL
                    title, img_url = extract_title_and_image(result_url)
                    
                    if title:
                        image_name = f"{search_term}_{search_id}.jpg"
                        if img_url:
                            # Download the image
                            download_image(img_url, save_dir, image_name)
                        
                        # Save title and image name
                        saved_data.append({
                            'npc_id': search_id,
                            'npc_slug': search_term,
                            'title': title,
                            'image_name': image_name if img_url else None
                        })
                    else:
                        print(f"Could not extract title or image for {search_term}")
                else:
                    print(f"No search result found for {search_term}")
            else:
                print(f"Invalid search term for ID: {search_id}")
        
        # Save the collected data (title and image name) as JSON or print it out
        with open('saved_data.json', 'w') as outfile:
            json.dump(saved_data, outfile, indent=4)
            print("Saved title and image data to 'saved_data.json'")

if __name__ == "__main__":
    main()
