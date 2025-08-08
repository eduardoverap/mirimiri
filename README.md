# Mirimiri

Mirimiri is an MVC application based on JavaScript and PHP. It allows users to extract kanjis from a text, view these on a table, and import data from the KANJIDIC XML database (not included here).

---

## Installation

### Requirements

- PHP 8.0+  
- Apache server (or a compatible setup like XAMPP, MAMP, etc.)  
- Composer

### Setup Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/eduardoverap/mirimiri.git

2. **Install dependencies**

   ```bash
   composer install

3. **Deploy**

   - Option 1: Copy the project folder into the "htdocs/" directory of your XAMPP/MAMP setup.
   - Option 2: Mount the folder in a PHP + Apache container.

4. **Launch the application**

   Open your browser and navigate to http://localhost/mirimiri

5. **Get the KANJIDIC XML database**

   To get the kanji information, you need to import the KANJIDIC database. Download it from the [EDRDG wiki](https://www.edrdg.org/wiki/index.php/KANJIDIC_Project). Uncompress the TAR.GZ and put the XML in the /database/ folder. Then go to "My database" in the application and proceed with the import.

## Project Status

This is an early alpha version. Some core functionality is implemented, but additional improvements and features are planned.

## License

This project is licensed under the MIT License.